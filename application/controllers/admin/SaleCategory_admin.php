<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class SaleCategory_admin extends CI_Controller
{
    public  $emptyCategoryList = array();
    private $data              = array();
    public  $message;
    public  $result;
    public  $urlArr;

    /** @var  Index_model */
    public $index_model;
    /** @var  Sale_model */
    public $sale_model;
    /** @var  Login_model */
    public $login_model;
    /** @var  SaleCategory_model */
    public $saleCategory_model;
    /** @var  Edit_menu_model */
    public $edit_menu_model;
    /** @var  Assign_model */
    public $assign_model;
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

        $this->emptyCategoryList = array(
            'id'     => null,
            'name'   => null,
            'status' => null
        );

        $this->result  = array("success" => null, "message" => null, "data" => null);
        $this->urlArr  = explode('/', $_SERVER['REQUEST_URI']);
        $this->message = null;
    }

    public function index()
    {
        $categoryListWithProductList = $this->saleCategory_model->getCategoryListWithProductListAdmin();
        $categoriesToProductsMap     = $this->_prepareCategoriesToProductsMap($categoryListWithProductList);

        $contentData = [
            'title'                   => 'Springconsulting - app-angular',
            'categoriesToProductsMap' => $categoriesToProductsMap,
            'message'                 => $this->message
        ];

        $data = [
            'menu'    => $this->load->view(MENU_ADMIN, '', true),
            'content' => $this->load->view('app-angular/sale_category/show', $contentData, true)
        ];

        $this->load->view('layout_admin', $data);
    }

    public function editNumber($id)
    {
        $sequenceNum = ArrayHelper::arrayGet($_REQUEST, 'sequence_num');
        $this->saleCategory_model->update($id, ['sequence_num' => $sequenceNum]);

        redirect('backend/sale_category');
    }

    private function _prepareCategoriesToProductsMap(array $categoryListWithProductList)
    {
        $map = [];

        foreach ($categoryListWithProductList as $categoryData) {
            $map[ArrayHelper::arrayGet($categoryData, 'id')]['data']                = $categoryData;
            $map[ArrayHelper::arrayGet($categoryData, 'id')]['sale_product_list'][] = $categoryData;
        }

        return $map;
    }

    public function edit($id = null)
    {
        $categoryData               = array();
        $assignedSaleProductList = array();
        $title                   = "Добавить sale category";

        $saleProductList = $this->sale_model->getListFromTable('sale_product');

        if ($id) {
            $categoryData                   = $this->saleCategory_model->getFromTableByParams(['id' => $id], 'sale_category');
            $assignedSaleProductDataList = $this->sale_model->getSaleProductListBySaleCategoryId($id);

            foreach ($assignedSaleProductDataList as $assignedSaleProductData) {
                $saleProductsId = ArrayHelper::arrayGet($assignedSaleProductData, 'sale_product_id');
                $assignedSaleProductList[$saleProductsId] = $assignedSaleProductData;
            }

            $title = "Редактировать категорию";
        }

        $content        = ArrayHelper::arrayGet($categoryData, 0, $this->emptyCategoryList);
        $url            = $this->index_model->prepareUrl($this->urlArr);
        $content['url'] = $url;

        $this->data = array(
            'title'                   => $title,
            'content'                 => $content,
            'menu_items'              => $this->edit_menu_model->childs,
            'assignedSaleProductList' => $assignedSaleProductList,
            'saleProductList'         => $saleProductList,
            'message'                 => $this->message
        );

        $data = array(
            'menu'    => $this->load->view(MENU_ADMIN, '', true),
            'content' => $this->load->view('app-angular/sale_category/edit', $this->data, true));

        $this->load->view('layout_admin', $data);
    }


    public function save()
    {
        $data                      = $params = array();
        $id                        = ArrayHelper::arrayGet($_REQUEST, 'id');
        $assignedNewSaleProductIds = ArrayHelper::arrayGet($_REQUEST, 'new_sale_product_id', array());
        $assignedOldSaleProductIds = ArrayHelper::arrayGet($_REQUEST, 'old_sale_product_id', array());

        try {
            $data['name'] = ArrayHelper::arrayGet($_REQUEST, 'name');

            if ($id) {
                $dataUpdate = array('status' => ArrayHelper::arrayGet($_REQUEST, 'status'));

                $data = array_merge($data, $dataUpdate);

                if (count($assignedNewSaleProductIds)) {
                    $this->_assignProcess($assignedNewSaleProductIds, $assignedOldSaleProductIds, $id);
                }

                $this->_update($id, $data);
            } else {
                $dataAdd = array('status' => STATUS_ON);

                $data = array_merge($data, $dataAdd);

                $id = $this->saleCategory_model->add($data);

                if ($assignedNewSaleProductIds) {
                    $this->_assignProcess($assignedNewSaleProductIds, $assignedOldSaleProductIds, $id);
                }

                redirect('backend/sale_category');
            }

        } catch (Exception $e) {
            $this->edit($id);
        }
    }

    private function _assignProcess($assignedNewSaleProductIds, $assignedOldSaleProductIds, $id)
    {
        $assignsArr = array(
            'newSourceIdArr'  => $assignedNewSaleProductIds,
            'oldSourceIdArr'  => $assignedOldSaleProductIds,
            'assignId'        => $id,
            'assignFieldName' => 'sale_category_id',
            'sourceFieldName' => 'sale_product_id',
            'table'           => 'sale_category_sale_product_assignment'
        );

        $this->assign_model->setAssignArr($assignsArr);
        $this->assign_model->addOrDeleteAssigns();
    }

    private function _update($id, $data)
    {
        if ($this->saleCategory_model->update($id, $data)) {
            redirect('backend/sale_category');
        } else {
            throw new Exception('Not updated');
        }
    }

    public function drop($id)
    {
        try {
            $this->saleCategory_model->del($id);
            $assignedSaleProductDataList = $this->sale_model->getSaleProductListBySaleCategoryId($id);

            if ($assignedSaleProductDataList) {
                foreach ($assignedSaleProductDataList as $assignedSaleProductsData) {
                    $this->saleCategory_model->delFromTable(
                        ArrayHelper::arrayGet($assignedSaleProductsData, 'sale_category_sale_product_assignment_id'),
                        'sale_category_sale_product_assignment'
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