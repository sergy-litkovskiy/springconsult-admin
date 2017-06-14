<?php
/**
 * @author Litkovskiy
 * @copyright 2010
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu_admin extends CI_Controller
{
    public $arrMenu        = array();
    public $arrMenuAdmin   = array();
    public $emptyMenuArr   = array();
    public $urlArr         = array();
    protected $tableName   = 'menu';
    /** @var  Menu_model */
    public $menu_model;
    /** @var  Edit_menu_model */
    public $edit_menu_model;
    /** @var  Sale_model */
    public $sale_model;
    /** @var  Assign_model */
    public $assign_model;

    function __construct()
    {
       parent::__construct();

       if(!$this->session->userdata('username') && !$this->session->userdata('loggedIn')){
           $this->login_model->logOut();
       }
       $this->arrMenu       = $this->_prepareMenu();
       $this->arrEditMenu   = $this->_prepareEditMenu();
       $this->emptyMenuArr  = array('id'                => null
                                   ,'parent'            => null
                                   ,'slug'              => null
                                   ,'color_class'              => null
                                   ,'icon_class'              => null
                                   ,'description'              => null
                                   ,'text'              => null
                                   ,'title'             => null
                                   ,'num_sequence'      => null
                                   ,'status'            => null
                                   ,'meta_description'  => null
                                   ,'meta_keywords'     => null);
       $this->urlArr = explode('/',$_SERVER['REQUEST_URI']);
    }
//---------------------------------------------------------------
    function index()
    {
       $this->data_menu     = array('menu' => $this->arrEditMenu);
       $contentArr          = $this->arrEditMenu;

       $this->data_arr      = array(
             'title'         => 'Springconsulting - редактировать меню'
            ,'contentMenu'   => $contentArr
       );
       $data = array(
                'menu'    => $this->load->view(MENU_ADMIN, $this->data_menu, true),
                'content' => $this->load->view('app-angular/menu_admin/show', $this->data_arr, true));

       $this->load->view('layout_admin', $data);

    }

//---------------------------------------------------------------
    function edit_menu_item($itemId = null)
    {
        $contentItems   = $itemId ? $this->edit_menu_model->getContentMenuById($itemId) : array(0);
        $contentArr     = $contentItems[0] ? $contentItems[0] : $this->emptyMenuArr;
        $url            = $this->_prepareUrl($this->urlArr);
        $contentArr['url'] = $url;
        $saleProductList = $this->sale_model->getList();
        $assignedSaleProductList = $itemId ? $this->sale_model->getSaleProductListByMenuId($itemId) : [];

        $this->data_arr      = array(
             'title'     => 'Редактировать раздел меню',
             'content'   => $contentArr,
             'saleProductList' => $saleProductList,
             'assignedSaleProductList' => array_values(array_map(function ($itemData) {
                 return ArrayHelper::arrayGet($itemData, 'id');
             }, $assignedSaleProductList))
        );

        $data = array(
                'menu'    => $this->load->view(MENU_ADMIN, '', true),
                'content' => $this->load->view('app-angular/menu_admin/item_edit', $this->data_arr, true));

        $this->load->view('layout_admin', $data);
    }

//---------------------------------------------------------------
    function edit_menu_subitem($subItemId = null)
    {
        $contentSubItems    = $subItemId ? $this->edit_menu_model->getContentMenuById($subItemId) : array(0);
        $contentArr         = $contentSubItems[0] ? $contentSubItems[0] : $this->emptyMenuArr;
        $itemtArr           = $this->edit_menu_model->getMenuItems();
        $url                = $this->_prepareUrl($this->urlArr);
        $contentArr['url']  = $url;
        $saleProductList = $this->sale_model->getList();
        $assignedSaleProductList = $subItemId ? $this->sale_model->getSaleProductListByMenuId($subItemId) : [];

        $this->data_arr      = array(
             'title'         => 'Редактировать подраздел меню',
             'itemArr'   => $itemtArr,
             'content'   => $contentArr,
             'saleProductList' => $saleProductList,
             'assignedSaleProductList' => array_values(array_map(function ($itemData) {
                 return ArrayHelper::arrayGet($itemData, 'id');
             }, $assignedSaleProductList))
        );

        $data = array(
                'menu'    => $this->load->view(MENU_ADMIN, '', true),
                'content' => $this->load->view('app-angular/menu_admin/subitem_edit', $this->data_arr, true));

        $this->load->view('layout_admin', $data);
    }
//---------------------------------------------------------------
    private function _prepareUrl($urlArr)
    {
        $countUrl = count($urlArr) - 1;
        $url = '';

        for($i = 1; $i <= $countUrl; $i++){
            $url .= $urlArr[$i];
            if($i < ($countUrl)){
                $url .= '/';
            }
        }

        return $url;
    }

    //---------------------------------------------------------------
    public function check_valid_menu()
    {
        $data  = $params = array();
        $params ['url'] = $_REQUEST['url'];
        $id             = isset($_REQUEST['id']) && $_REQUEST['id'] ? $_REQUEST['id'] : null;
        $parent         = isset($_REQUEST['parent']) ? $_REQUEST['parent'] : null;

        $assignedNewSaleProductIds = ArrayHelper::arrayGet($_REQUEST, 'new_sale_product_id', []);
        $assignedOldSaleProductIds = ArrayHelper::arrayGet($_REQUEST, 'old_sale_product_id', []);

        try{
            $this->_formValidation($parent);
            $data = $this->_prepareMenuDataForAddUpdate($_REQUEST);

            if($id){
                $params ['id']  = $id;
                $dataUpdate = array('num_sequence'    => $_REQUEST['num_sequence']
                                    ,'status'         => $_REQUEST['status']);
                $data = array_merge($data, $dataUpdate);

                if (count($assignedNewSaleProductIds)) {
                    $this->_assignProcess($assignedNewSaleProductIds, $assignedOldSaleProductIds, $id);
                }

                $this->_update($data, $params);
            } else {
                $dataAdd = array('num_sequence'    => '0'
                                ,'status'          => STATUS_ON);
                $data = array_merge($data, $dataAdd);

                $id = $this->edit_menu_model->add($data);

                if ($assignedNewSaleProductIds) {
                    $this->_assignProcess($assignedNewSaleProductIds, $assignedOldSaleProductIds, $id);
                }
            }
        } catch (Exception $e){
            isset($parent) ? $this->edit_menu_subitem($id) : $this->edit_menu_item($id);
        }
    }

    private function _assignProcess($assignedNewSaleProductIds, $assignedOldSaleProductIds, $id)
    {
        $assignsArr = array(
            'newSourceIdArr'  => $assignedNewSaleProductIds,
            'oldSourceIdArr'  => $assignedOldSaleProductIds,
            'assignId'        => $id,
            'assignFieldName' => 'menu_id',
            'sourceFieldName' => 'sale_product_id',
            'table'           => 'menu_sale_product_assignment'
        );

        $this->assign_model->setAssignArr($assignsArr);
        $this->assign_model->addOrDeleteAssigns();
    }

//---------------------------------------------------------------
private function _formValidation($parent = null)
{
    $rules = isset($parent) ? $this->_prepareSubMenuValidationRules() : $this->_prepareMenuValidationRules();
    $this->form_validation->set_rules($rules);

    $isValid = $this->form_validation->run();
    Common::assertTrue($isValid, 'Форма заполнена неверно');
}

//---------------------------------------------------------------
private function _prepareMenuValidationRules()
{
    return array(
                array(
                'field'	=> 'title',
                'label'	=> '<Название раздела>',
                'rules'	=> 'required'),
                array(
                'field'	=> 'slug',
                'label'	=> '<Алиас раздела>',
                'rules'	=> 'required'));
}

//---------------------------------------------------------------
private function _prepareSubMenuValidationRules()
{
    return array(
                array(
                'field'	=> 'parent',
                'label'	=> '<Название раздела>',
                'rules'	=> 'required'),
                array(
                'field'	=> 'title',
                'label'	=> '<Название подраздела>',
                'rules'	=> 'required'),
                array(
                'field'	=> 'slug',
                'label'	=> '<Алиас раздела>',
                'rules'	=> 'required'));
}

//---------------------------------------------------------------
private function _prepareMenuDataForAddUpdate($request)
{
    return array('meta_description' => trim($request['meta_description'])
                ,'meta_keywords'    => trim($request['meta_keywords'])
                ,'color_class'    => $request['color_class']
                ,'icon_class'    => $request['icon_class']
                ,'description'    => trim($request['description'])
                ,'title'            => trim($request['title'])
                ,'slug'             => trim($request['slug'])
                ,'text'             => trim($request['text'])
                ,'parent'           => isset($request['parent']) ? $request['parent'] : '0');
}

//---------------------------------------------------------------
public function drop($parent, $id)
{
    try{
        $this->edit_menu_model->del($id);
        $resultUpdate = $this->_updateNumSequence($parent);
        Common::assertTrue($resultUpdate, 'Not updated sequence number of menu item');
        redirect('backend/menu_admin');
    } catch (Exception $e){
        return false;
    }
}

//---------------------------------------------------------------
private function _updateNumSequence($parent)
{
    $data    = array();
    $menuArr = $this->edit_menu_model->getMenuItems($parent);
    foreach($menuArr as $key => $menu){
        $data['num_sequence'] = $key + 1;
        $this->edit_menu_model->update($menu['id'], $data);
    }
    return true;
}

//---------------------------------------------------------------
private function _add($data)
{
    if($this->edit_menu_model->addInTable($data, $this->tableName)){
        redirect('backend/menu_admin');
    } else{
        throw new Exception('Not inserted menu item');
    }
}

//---------------------------------------------------------------
private function _update($data, $params)
{
    if($this->edit_menu_model->updateInTable($params['id'], $data, $this->tableName)){
        redirect('backend/menu_admin');
    } else{
        throw new Exception('Not updated menu item');
    }
}

//---------------------------------------------------------------
     public function ajax_change_status()
    {
        $data = $arrData = array();
        $data['status']     = $_REQUEST['status'];
        $arrData['id']      = $_REQUEST['id'];
        $arrData['table']   = $_REQUEST['table'];

        $this->_ajax_menu_process($data, $arrData);
    }

//---------------------------------------------------------------
    public function ajax_get_main_item()
    {
       $result = $this->edit_menu_model->getMenuItems();

       print json_encode($result);
       exit;
    }

//---------------------------------------------------------------
    public function ajax_change_sequence_num_menu()
    {
        $dataArr    = array();
        $resArr     = array('success' => null, 'error' => null);

        $dataArr[0]['id']              = $_REQUEST['current_id'];
        $dataArr[1]['id']              = $_REQUEST['second_id'];
        $dataArr[0]['num_sequence']    = $_REQUEST['current_num_seq'];
        $dataArr[1]['num_sequence']    = $_REQUEST['second_num_seq'];
        try{
            foreach($dataArr as $data){
                $result = $this->edit_menu_model->update($data['id'], array('num_sequence' => $data['num_sequence']));
                Common::assertTrue($result, 'Not updated');
                $resArr = array("success" => true, "error" => false);
            }
        } catch (Exeption $e) {
            $resArr = array("success" => false, "error" => $e->getMessage());
        }

        print json_encode($resArr);
        exit;
    }
//---------------------------------------------------------------

    private function _prepareMenu()
    {
       return $this->menu_model->childs;
    }

//---------------------------------------------------------------

    private function _prepareEditMenu()
    {
       return $this->edit_menu_model->childs;
    }

//---------------------------------------------------------------

	public function logout()
	{
            return $this->login_model->logOut();
	}

}