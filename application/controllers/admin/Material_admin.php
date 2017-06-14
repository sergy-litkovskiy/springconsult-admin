<?php
/**
 * @author Litkovskiy
 * @copyright 2012
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Material_admin extends CI_Controller
{
    public $message;
    private $emptyMaterialsArr;

    public function __construct()
    {

        parent::__construct();
        if (!$this->session->userdata('username') && !$this->session->userdata('loggedIn')) {
            $this->login_model->logOut();
        }

        $this->emptyMaterialsArr = array(
            'id'         => null
        , 'rus_name'     => null
        , 'file_path'    => null
        , 'num_sequence' => null
        , 'status'       => null);

        $this->urlArr  = explode('/', $_SERVER['REQUEST_URI']);
        $this->message = null;
        $this->result  = array("success" => null, "message" => null, "data" => null);
    }


    public function material_list()
    {
        $this->data_menu     = array('menu' => $this->edit_menu_model->childs);
        $contentArr          = $this->index_model->getMaterialListAdmin();
        $contentAndAssignArr = $this->_prepareContentAndAssignsArr($contentArr);

        $this->data_arr = array(
            'title' => 'Springconsulting - app-angular'
        , 'content' => $contentAndAssignArr['content']
        , 'assigns' => $contentAndAssignArr['assigns']
        , 'message' => $this->message
        );

        $data = array(
            'menu'    => $this->load->view(MENU_ADMIN, $this->data_menu, true),
            'content' => $this->load->view('app-angular/material/show', $this->data_arr, true));

        $this->load->view('layout_admin', $data);

    }


    public function material_edit($id = null)
    {
        $materials       = array(0);
        $assignMaterials = array();
        $assignTagArr    = array();
        $title           = "Добавить статью";
        if ($id) {
            $materials          = $this->index_model->getMaterialDetailsAdmin($id);
            $assignMaterialsArr = $this->index_model->getAssignMaterialsByMaterialIdAdmin($id);
            foreach ($assignMaterialsArr as $assignArt) {
                $assignMaterials[$assignArt['materials_id']][] = $assignArt['menu_id'];
            }
            if ($assignMaterials) {
                $assignMaterials = $assignMaterials[$id];
            }
            $title        = "Редактировать материал";
            $assignTagArr = $this->index_model->getAssignTagArr($id, 'materials_tag', 'materials_id');
        }

        $contentArr        = $materials[0] ? $materials[0] : $this->emptyMaterialsArr;
        $url               = $this->index_model->prepareUrl($this->urlArr);
        $contentArr['url'] = $url;

        $this->data_arr = array(
            'title'          => $title
        , 'content'          => $contentArr
        , 'menu_items'       => $this->edit_menu_model->childs
        , 'assign_materials' => $assignMaterials
        , 'assign_tag_arr'   => $assignTagArr
        , 'message'          => $this->message
        );

        $data = array(
            'menu'    => $this->load->view(MENU_ADMIN, '', true),
            'content' => $this->load->view('app-angular/material/edit', $this->data_arr, true));

        $this->load->view('layout_admin', $data);
    }


    public function check_valid_materials()
    {
        $fileName       = null;
        $uploadPath     = './materials/';
        $data           = $params = array();
        $id             = isset($_REQUEST['id']) && $_REQUEST['id'] ? $_REQUEST['id'] : null;
        $arrArticlesTag = !empty($_REQUEST['tag']) ? $_REQUEST['tag'] : array();

        try {
            if ($_FILES['file_path']['size'] > 0) {
                $fileName = $this->index_model->tryUploadFile($_FILES['file_path'], $uploadPath);
            }

            $assignMenuIdArr = isset($_REQUEST['menu']) && $_REQUEST['menu'] ? $_REQUEST['menu'] : array();
            $oldAssignMenuId = isset($_REQUEST['old_assign_id']) && $_REQUEST['old_assign_id'] ? $_REQUEST['old_assign_id'] : array();

            if ($id) {
                $this->_formMaterialsValidation();
                $params ['id'] = $id;
                $dataUpdate    = array(
                    'rus_name'   => $_REQUEST['rus_name']
                , 'num_sequence' => $_REQUEST['num_sequence']
                , 'status'       => $_REQUEST['status']);

                $data = $fileName ? array_merge(array('file_path' => $fileName), $dataUpdate) : $dataUpdate;

                if (count($assignMenuIdArr)) {
                    $this->_assignProcess($assignMenuIdArr, $oldAssignMenuId, $id);
                }

                if (count($arrArticlesTag)) {
                    $this->tags_model->tagProcess($arrArticlesTag, $id, 'materials_tag', 'materials_id');
                }

                $this->_updateMaterials($data, $params);
            } else {
                $this->_formMaterialsValidation();
                Common::assertTrue($fileName, 'Форма заполнена неверно');

                $data = array(
                    'rus_name'   => $_REQUEST['rus_name']
                , 'num_sequence' => '0'
                , 'status'       => STATUS_ON
                , 'file_path'    => $fileName);

                $id = $this->_addMaterials($data);
                Common::assertTrue($id, 'Форма заполнена неверно');

                if (count($assignMenuIdArr)) {
                    $this->_assignProcess($assignMenuIdArr, $oldAssignMenuId, $id);
                }

                if (count($arrArticlesTag)) {
                    $this->tags_model->tagProcess($arrArticlesTag, $id, 'materials_tag', 'materials_id');
                }

                redirect('backend/material');
            }
        } catch (Exception $e) {
            $this->message = $e->getMessage();
            $this->material_edit($id);
        }
    }


    private function _assignProcess($assignMenuIdArr, $oldAssignMenuId, $id)
    {
        $assignsArr = array(
            'newSourceIdArr' => $assignMenuIdArr
        , 'oldSourceIdArr'   => $oldAssignMenuId
        , 'assignId'         => $id
        , 'assignFieldName'  => 'materials_id'
        , 'sourceFieldName'  => 'menu_id'
        , 'table'            => 'assign_materials');

        $this->assign_model->setAssignArr($assignsArr);
        $this->assign_model->addOrDeleteAssigns();
    }


    private function _formMaterialsValidation()
    {
        $rules = array(
            array(
                'field' => 'rus_name',
                'label' => '<Название>',
                'rules' => 'required'));
        $this->form_validation->set_rules($rules);

        $isValid = $this->form_validation->run();
        Common::assertTrue($isValid, 'Форма заполнена неверно');
    }


    public function material_drop($id)
    {
        try {
            $this->index_model->dropWithFile($id, $_REQUEST['file'], 'materials');
            $this->result['success'] = true;
        } catch (Exception $e) {
            $this->result['message'] = $e->getMessage();
        }

        print json_encode($this->result);
        exit;
    }


    private function _addMaterials($data)
    {
        return $this->index_model->addInTable($data, 'materials');
    }


    private function _updateMaterials($data, $params)
    {
        $isUpdated = $this->index_model->updateInTable($params['id'], $data, 'materials');
        Common::assertTrue($isUpdated, 'Not updated');
        if (isset($params['file']) && $params['file']) {
            unlink('./materials/' . $params['file']);
        }
        redirect('backend/material');
    }


    private function _prepareContentAndAssignsArr($contentArr)
    {
        $contentAndAssignArr = $newContentArr = $assignedDivizionArr = array();
        foreach ($contentArr as $content) {
            $newContentArr[$content['id']]         = $content;
            $assignedDivizionArr[$content['id']][] = $content['slug_title'];
        }
        $contentAndAssignArr['content'] = $newContentArr;
        $contentAndAssignArr['assigns'] = $assignedDivizionArr;

        return $contentAndAssignArr;
    }
}