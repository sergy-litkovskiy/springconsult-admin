<?php
/**
 * @author Litkovskiy
 * @copyright 2012
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Announce_admin extends CI_Controller
{
    public $message;
    private $emptyAnnounceArr;

    public function __construct()
    {
    
        parent::__construct();
        if(!$this->session->userdata('username') && !$this->session->userdata('loggedIn')){
            $this->login_model->logOut();
        }

        $this->emptyAnnounceArr = array(
            'id'            => null
            ,'text'         => null
            ,'status'       => null
            ,'created_at'   => null);

        $this->urlArr = explode('/',$_SERVER['REQUEST_URI']);
        $this->message  = null;
    }


    public function announce_list()
    {
        $title              = "Анонсы";
        $announceArr = $this->index_model->getListFromTable('announcement');

        $this->data_arr     = array(
            'title'     => $title
            ,'content'  => $announceArr
        );

        $data = array(
            'menu'    => $this->load->view(MENU_ADMIN, '', true),
            'content' => $this->load->view('app-angular/announce/show', $this->data_arr, true));

        $this->load->view('layout_admin', $data);
    }


    public function announce_drop($id)
    {
        try{
            $this->index_model->delFromTable($id, 'announcement');
            $this->result['success'] = true;
        } catch (Exception $e){
            $this->result['message'] = $e->getMessage();
        }

        print json_encode($this->result);
        exit;
    }


    public function announce_edit($id = null)
    {
        $salePage  = null;
        $title        = "Создать анонс";
        if($id){
            $salePage  = $this->index_model->getFromTableByParams(array('id' => $id), 'announcement');
            $title     = "Редактировать анонс";
        }

        $contentArr         = $salePage[0] ? $salePage[0] : $this->emptyAnnounceArr;
        $url                = $this->index_model->prepareUrl($this->urlArr);
        $contentArr['url']  = $url;

        $this->data_arr     = array(
             'title'            => $title
            ,'content'          => $contentArr
//            ,'menu_items'       => $this->edit_menu_model->childs
            ,'message'          => $this->message
            );

        $data = array(
            'menu'    => $this->load->view(MENU_ADMIN, '', true),
            'content' => $this->load->view('app-angular/announce/edit', $this->data_arr, true));

        $this->load->view('layout_admin', $data);
    }


    public function check_valid_announce()
    {
        $data           = $params = array();
        $id             = isset($_REQUEST['id']) && $_REQUEST['id'] ? $_REQUEST['id'] : null;

        try{
            $this->_formAnnounceValidation();
            $dataMain       = array('text' => $_REQUEST['text']);

            if($id){
                $params['id']  = $id;
                $data          = array_merge($dataMain, array(
                    'created_at'    => $_REQUEST['created_at'],
                    'status'        => $_REQUEST['status']));
                $this->_updateAnnouncePage($data, $params);
            } else {
                $data = array_merge($dataMain, array(
                    'created_at'    => date('Y-m-d H:i:s')
                    ,'status'       => STATUS_ON));

                $this->_addAnnouncePage($data);
            }
        } catch (Exception $e){
            $this->message = $e->getMessage();
            $this->announce_edit($id);
        }
    }


    private function _formAnnounceValidation()
    {
        $rules = array(
            array(
                'field'	=> 'text',
                'label'	=> '<Text>',
                'rules'	=> 'required'),
            array(
                'field'	=> 'text',
                'label'	=> '<Text>',
                'rules'	=> 'required'));

        $this->form_validation->set_rules($rules);

        $isValid = $this->form_validation->run();
        Common::assertTrue($isValid, 'Форма заполнена неверно');
    }


    private function _addAnnouncePage($data)
    {
        $id = $this->index_model->addInTable($data, 'announcement');
        Common::assertTrue($id, 'Информация не добавлена в базу');
        redirect('backend/announce_list');
    }


    private function _updateAnnouncePage($data, $params)
    {
        $isUpdated = $this->index_model->updateInTable($params['id'], $data, 'announcement');
        Common::assertTrue($isUpdated, 'Not updated');
        redirect('backend/announce_list');
    }
}