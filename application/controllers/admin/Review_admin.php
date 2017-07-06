<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Review_admin extends CI_Controller
{
    public $emptyReviewList = array();
    public $message;
    public $result;
    public $urlArr;

    /** @var  Index_model */
    public $index_model;
    /** @var  Sale_model */
    public $sale_model;
    /** @var  SalePage_model */
    public $salePage_model;
    /** @var  Assign_model */
    public $assign_model;
    /** @var  Login_model */
    public $login_model;
    /** @var  Review_model */
    public $review_model;
    /** @var  Edit_menu_model */
    public $edit_menu_model;
    /** @var  CI_Form_validation */
    public $form_validation;
    /** @var  CI_Session */
    public $session;

    public function __construct()
    {
        parent::__construct();

        if (!$this->session->userdata('username') && !$this->session->userdata('loggedIn')) {
            $this->login_model->logOut();
        }

        $this->emptyReviewList = array(
            'id'     => null,
            'author' => null,
            'image'  => null,
            'text'   => null,
            'status' => null,
            'date'   => null,
        );

        $this->result  = array("success" => null, "message" => null, "data" => null);
        $this->urlArr  = explode('/', $_SERVER['REQUEST_URI']);
        $this->message = null;
    }

    public function index()
    {
        $reviewListWithAssignedItems = $this->review_model->getReviewListWithAssignedItemsAdmin();
        $reviewsToAssignedItemsMap      = $this->_prepareReviewsWithAssignedItemsMap($reviewListWithAssignedItems);

        $contentData = array(
            'title'                => 'Springconsulting - app-angular',
            'reviewsToAssignedItemsMap' => $reviewsToAssignedItemsMap,
            'message'              => $this->message
        );

        $data = array(
            'menu'    => $this->load->view(MENU_ADMIN, '', true),
            'content' => $this->load->view('app-angular/review/show', $contentData, true));

        $this->load->view('layout_admin', $data);
    }

    private function _prepareReviewsWithAssignedItemsMap(array $reviewListWithPageList)
    {
        $map = array();

        foreach ($reviewListWithPageList as $reviewData) {
            $saleProductId = ArrayHelper::arrayGet($reviewData, 'sale_product_id');
            $menuId = ArrayHelper::arrayGet($reviewData, 'menu_id');

            $map[ArrayHelper::arrayGet($reviewData, 'id')]['data']                = $reviewData;
            $map[ArrayHelper::arrayGet($reviewData, 'id')]['sale_product_list'][$saleProductId] = $reviewData;
            $map[ArrayHelper::arrayGet($reviewData, 'id')]['menu_list'][$menuId] = $reviewData;
        }

        return $map;
    }

    public function edit($id = null)
    {
        $reviewData              = [];
        $assignedMenuList = [];
        $assignedSaleProductIdList = [];
        $title                   = "Добавить отзыв";

        $saleProductList = $this->sale_model->getList();
        $menuList = $this->sale_model->getListFromTable('menu');

        if ($id) {
            $reviewData                  = $this->review_model->get($id);
            $assignedSaleProductIdList = $this->sale_model->getAssignedSaleProductIdListByReviewId($id);
            $assignedMenuDataList = $this->edit_menu_model->getAssignedMenuListByReviewId($id);

            $assignedSaleProductIdList = array_map(function ($assignedSaleProductData) {
                return ArrayHelper::arrayGet($assignedSaleProductData, 'sale_product_id');
            }, $assignedSaleProductIdList);

            foreach ($assignedMenuDataList as $assignedMenuData) {
                $menuId = ArrayHelper::arrayGet($assignedMenuData, 'id');
                $assignedMenuList[$menuId] = $assignedMenuData;
            }

            $title = "Редактировать reviews";
        }

        $content = ArrayHelper::arrayGet($reviewData, 0, $this->emptyReviewList);

        $url            = $this->index_model->prepareUrl($this->urlArr);
        $content['url'] = $url;

        $contentData = array(
            'title'                   => $title,
            'content'                 => $content,
            'menu_items'              => $this->edit_menu_model->childs,
            'assignedSaleProductIdList' => $assignedSaleProductIdList,
            'assignedMenuList' => $assignedMenuList,
            'saleProductList'         => $saleProductList,
            'articleItemList'         => $menuList,
            'message'                 => $this->message
        );

        $data = array(
            'menu'    => $this->load->view(MENU_ADMIN, '', true),
            'content' => $this->load->view('app-angular/review/edit', $contentData, true));

        $this->load->view('layout_admin', $data);
    }


    public function save()
    {
        $data = $params = $saleProductDataToAssign = $menuDataToAssign = [];
        $id   = ArrayHelper::arrayGet($_REQUEST, 'id');

        $assignedNewSaleProductIds = ArrayHelper::arrayGet($_REQUEST, 'new_sale_product_id', []);
        $assignedOldSaleProductIds = ArrayHelper::arrayGet($_REQUEST, 'old_sale_product_id', []);

        $assignedNewMenuIds = ArrayHelper::arrayGet($_REQUEST, 'new_menu_id', []);
        $assignedOldMenuIds = ArrayHelper::arrayGet($_REQUEST, 'old_menu_id', []);

        try {
            $data['author'] = ArrayHelper::arrayGet($_REQUEST, 'author');
            $data['text']   = trim(ArrayHelper::arrayGet($_REQUEST, 'text'));
            $data['image']  = ArrayHelper::arrayGet($_REQUEST, 'image');
            $data['date']   = ArrayHelper::arrayGet($_REQUEST, 'date', date('Y-m-d H:i:s'));

            if ($assignedNewSaleProductIds) {
                $saleProductDataToAssign = [
                    'assignFieldName' => 'review_id',
                    'sourceFieldName' => 'sale_product_id',
                    'table'           => 'sale_product_review_assignment'
                ];
            }

            if ($assignedNewMenuIds) {
                $menuDataToAssign = [
                    'assignFieldName' => 'review_id',
                    'sourceFieldName' => 'menu_id',
                    'table'           => 'menu_review_assignment'
                ];
            }

            if ($id) {
                $dataUpdate = array('status' => ArrayHelper::arrayGet($_REQUEST, 'status'));

                $data = array_merge($data, $dataUpdate);

                if ($saleProductDataToAssign) {
                    $dataToAssign = array_merge($saleProductDataToAssign, ['assignId' => $id]);

                    $this->_assignProcess($assignedNewSaleProductIds, $assignedOldSaleProductIds, $dataToAssign);
                }

                if ($menuDataToAssign) {
                    $dataToAssign = array_merge($menuDataToAssign, ['assignId' => $id]);

                    $this->_assignProcess($assignedNewMenuIds, $assignedOldMenuIds, $dataToAssign);
                }

                $this->_update($id, $data);
            } else {
                $dataAdd = array('status' => STATUS_ON);

                $data = array_merge($data, $dataAdd);

                $id = $this->review_model->add($data);
                Common::assertTrue($id, 'Форма заполнена неверно');

                if ($saleProductDataToAssign) {
                    $dataToAssign = array_merge($saleProductDataToAssign, ['assignId' => $id]);

                    $this->_assignProcess($assignedNewSaleProductIds, $assignedOldSaleProductIds, $dataToAssign);
                }

                if ($menuDataToAssign) {
                    $dataToAssign = array_merge($menuDataToAssign, ['assignId' => $id]);

                    $this->_assignProcess($assignedNewMenuIds, $assignedOldMenuIds, $dataToAssign);
                }

                redirect('backend/review');
            }

        } catch (Exception $e) {
            $this->edit($id);
        }
    }

    private function _assignProcess(array $assignedNewIds, array $assignedOldIds, array $data)
    {
        $assignsArr = array_merge(
            $data, array(
                'newSourceIdArr'  => $assignedNewIds,
                'oldSourceIdArr'  => $assignedOldIds,
            )
        );

        $this->assign_model->setAssignArr($assignsArr);
        $this->assign_model->addOrDeleteAssigns();
    }

    private function _update($id, $data)
    {
        if ($this->review_model->update($id, $data)) {
            redirect('backend/review');
        } else {
            throw new Exception('Not updated');
        }
    }

    public function drop($id)
    {
        try {
            $this->review_model->del($id);
            $assignedSaleProductDataList = $this->sale_model->getAssignedSaleProductListByReviewId($id);

            if ($assignedSaleProductDataList) {
                foreach ($assignedSaleProductDataList as $assignedSaleProductData) {
                    $this->review_model->delFromTable(
                        ArrayHelper::arrayGet($assignedSaleProductData, 'sale_product_review_assignment_id'),
                        'sale_product_review_assignment'
                    );
                }
            }
            $this->result['success'] = true;
        } catch (Exception $e) {
            $this->result['message'] = $e->getMessage();
        }

        print json_encode($this->result);
        exit;
    }
}