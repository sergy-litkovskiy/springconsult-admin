<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Shop extends MY_Controller
{
    protected $entityName = 'shop';

    public function index()
    {
        $mainData = $this->menu_model->get(MENU_TOP_LEVEL_ID_SHOP);
        $mainData = ArrayHelper::arrayGet($mainData, 0, []);

        $metaData = $this->prepareMetaData($mainData);

        $categoryList     = $this->saleCategory_model->getCategoryListWithProductList();
        $saleCategoryList = $this->_makeMainDataToProductMap($categoryList, 'makeCategoryMainData');
        $data             = [
            'currentItemName'  => $this->entityName,
            'data'             => $mainData,
            'saleCategoryList' => $saleCategoryList,
            'metaData'         => $metaData,
            'pageTitle'        => 'Магазин'
        ];

        $data = array_merge($data, $this->baseResult);

        $this->twig->display($this->entityName . '/index.html', $data);
    }

    public function show($saleProductId)
    {
        $saleProductData = $this->sale_model->get($saleProductId);
        $saleProductImageList = $this->saleProductImage_model->getSaleProductImageBySaleProductId($saleProductId);
        $reviewList = $this->review_model->getReviewListBySaleProductId($saleProductId);
        $productData = ArrayHelper::arrayGet(array_values($saleProductData), 0);

        //get only menu image
        $imageData = array_filter($saleProductImageList, function ($imageData) {
            return (bool)ArrayHelper::arrayGet($imageData, 'is_main');
        });

        $productData['image'] = ArrayHelper::arrayGet($imageData, 0);
        $mainImage = ArrayHelper::arrayGet($productData, 'image.image');

        //extend menu data for page with menu image to create metaData for FB
        ArrayHelper::arraySet($saleProductData, '0.image', $mainImage);

        $metaData = $this->prepareMetaData(ArrayHelper::arrayGet($saleProductData, 0, []));

        $data = [
            'currentItemName' => 'saleProduct',
            'metaData'        => $metaData,
            'reviewList'      => $reviewList,
            'saleProductImageList' => $saleProductImageList,
            'data'            => $productData,
            'pageTitle'       => ArrayHelper::arrayGet($saleProductData, '0.title')
        ];

        $data = array_merge($data, $this->baseResult);

        $this->twig->display($this->entityName . '/show.html', $data);
    }

    private function _makeMainDataToProductMap($dataList, $mainDataMethod)
    {
        $map = [];

        foreach ($dataList as $data) {
            $saleProductId = ArrayHelper::arrayGet($data, 'sale_product_id');
            $mainDataId     = ArrayHelper::arrayGet($data, 'id');

            if (!ArrayHelper::arrayHas($map, $mainDataId)) {
                $map[$mainDataId]['data'] = $this->$mainDataMethod($data);
            }

            if (!$saleProductId) {
                continue;
            }

            $map[$mainDataId]['productList'][$saleProductId] =
                $saleProductId
                    ? $this->makeProductsData($data)
                    : [];
        }

        return $map;
    }

    private function makeCategoryMainData(array $mainData)
    {
        return [
            'id'   => ArrayHelper::arrayGet($mainData, 'id'),
            'name' => ArrayHelper::arrayGet($mainData, 'name'),
        ];
    }

    private function makeSalePageMainData(array $mainData)
    {
        return [
            'id'    => ArrayHelper::arrayGet($mainData, 'id'),
            'title' => ArrayHelper::arrayGet($mainData, 'title'),
            'slug'  => ArrayHelper::arrayGet($mainData, 'slug'),
            'text1' => ArrayHelper::arrayGet($mainData, 'text1'),
            'text2' => ArrayHelper::arrayGet($mainData, 'text2'),
        ];
    }

    private function makeProductsData($categoryData)
    {
        return [
            'id'           => ArrayHelper::arrayGet($categoryData, 'sale_product_id'),
            'title'        => ArrayHelper::arrayGet($categoryData, 'sale_product_title'),
            'label'        => ArrayHelper::arrayGet($categoryData, 'sale_product_label'),
            'description'  => ArrayHelper::arrayGet($categoryData, 'sale_product_description'),
            'image'  => ArrayHelper::arrayGet($categoryData, 'sale_product_image'),
            'price'        => ArrayHelper::arrayGet($categoryData, 'sale_product_price'),
            'gift'         => ArrayHelper::arrayGet($categoryData, 'sale_product_gift'),
            'slug' => ArrayHelper::arrayGet($categoryData, 'sale_product_slug'),
        ];
    }

    public function productPayment()
    {
        $recipientData = [
            'name'       => trim(strip_tags($this->input->post('name'))),
            'email'      => trim(strip_tags($this->input->post('email'))),
            'phone'      => trim(strip_tags($this->input->post('phone'))),
            'created_at' => date('Y-m-d H:i:s'),
            'confirmed'  => STATUS_ON
        ];

        $extData = [
            'productId' => trim(strip_tags($this->input->post('productId'))),
            'price'     => trim(strip_tags($this->input->post('price'))),
            'title'     => trim(strip_tags($this->input->post('title'))),
        ];

        $saleHistoryData['created_at']       = date('Y-m-d H:i:s');
        $saleHistoryData['sale_product_id'] = trim(strip_tags($this->input->post('productId')));

        return $this->processPayment($recipientData, $saleHistoryData, $extData);
    }

    public function processPayment($recipientData, $saleHistoryData, $extData)
    {
        $result     = ['success' => true, 'error' => false];
        $errLogData = [];

        try {
            $this->validatePaymentData($recipientData);

            $recipient = $this->recipient_model->getRecipientData($recipientData, true);

            $saleHistoryData['recipients_id']    = ArrayHelper::arrayGet($recipient, 'id');
            $saleHistoryData['payment_state']    = NULL;
            $saleHistoryData['payment_status']   = '';
            $saleHistoryData['payment_trans_id'] = '';
            $saleHistoryData['payment_message']  = '';

            $saleHistoryId = $this->saleHistory_model->add($saleHistoryData);
            Common::assertTrue(
                $saleHistoryId,
                'К сожалению, при регистрации произошла ошибка. Пожалуйста, попробуйте еще раз'
            );

            $mailData = array_merge($extData, $recipientData);
            $this->mailer_model->sendAdminSaleEmailMessage($mailData);
        } catch (Exception $e) {
            $result['error']   = true;
            $result['success'] = false;
            $result['message'] = $e->getMessage();

            $errLogData['resource_id'] = ERROR_PAYMENT_REGISTRATION;
            $errLogData['text']        = sprintf(
                '%s - Продающая страница: %s (%s - %s)',
                $e->getMessage(),
                ArrayHelper::arrayGet($saleHistoryData, 'sale_product_id'),
                ArrayHelper::arrayGet($recipientData, 'name'),
                ArrayHelper::arrayGet($recipientData, 'email')
            );

            $errLogData['created_at'] = date('Y-m-d H:i:s');
            $this->errorLog_model->add($errLogData);
        }

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($result));
    }

    private function validatePaymentData($data)
    {
        if (!$userName = ArrayHelper::arrayGet($data, 'name')) {
            throw new Exception('Поле Имя обязательно');
        }

        $email = ArrayHelper::arrayGet($data, 'email');
        $phone = ArrayHelper::arrayGet($data, 'phone');

        if (!$email && !$phone) {
            throw new Exception('Заполните поле Email или Телефон, чтобы мы могли связаться с вами');
        }

        if ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Неверный формат Email');
        }

        return true;
    }

    /**
     * Generate path to image for FB using product's images
     * @param $data
     * @return string
     */
    protected function makeFbImage($data)
    {
        $imageName = ArrayHelper::arrayGet($data, 'image');

        if (is_null($imageName)) {
            //return default fbImage
            return parent::makeFbImage($data);
        }

        return ImageHelper::makeFbImage('img/sale_product/', $imageName);
    }

    protected function makeMetaType()
    {
        return 'product';
    }
}