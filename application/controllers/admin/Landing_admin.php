<?php
/**
 * @author Litkovskiy
 * @copyright 2012
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Landing_admin extends CI_Controller
{
    public $message;
    private $landingPageArr;
    private $landingArticleArr;

    public function __construct()
    {
    
        parent::__construct();
        if(!$this->session->userdata('username') && !$this->session->userdata('loggedIn')){
            $this->login_model->logOut();
        }

        $this->landingPageArr = array(
            'id'                    => null
            ,'unique'               => null
            ,'title'                => null
            ,'title_description'    => null
            ,'page_text'            => null
            ,'letter_text'          => null
            ,'status'               => null
            ,'created_at'           => null
            ,'updated_at'           => null);

        $this->landingArticleArr = array(
            'id'                    => null
            ,'title'                => null
            ,'slug'                 => null
            ,'text'                 => null
            ,'landing_page_id'      => null
            ,'password_mp3'         => null
            ,'link_mp3'             => null
            ,'status'               => null
            ,'created_at'           => null);

        $this->urlArr = explode('/',$_SERVER['REQUEST_URI']);
        $this->message  = null;
        $this->result   = array("success" => null, "message" => null, "data" => null);
    }

////////////////////////////////LANDING//////////////////////////
    public function landing_list()
    {
        $title          = "Редактировать лэндинги";
        $landingList    = $this->index_model->getListFromTable('landing_page');
        foreach($landingList as $key => $landing){
            $landingList[$key]['registred_list'] = $this->landing_model->getLandingRegistredRecipients($landing['id']);
        }

        $this->data_arr     = array(
            'title'         => $title
            ,'content'      => $landingList
            ,'menu_items'   => $this->edit_menu_model->childs
        );

        $data = array(
            'menu'    => $this->load->view(MENU_ADMIN, '', true),
            'content' => $this->load->view('app-angular/landing/show', $this->data_arr, true));

        $this->load->view('layout_admin', $data);
    }


    public function landing_drop($id)
    {
        try{
            $this->index_model->delFromTable($id, 'landing_page');
            $this->result['success'] = true;
        } catch (Exception $e){
            $this->result['message'] = $e->getMessage();
        }

        print json_encode($this->result);
        exit;
    }


    public function landing_edit($id = null)
    {
        $landingPage  = null;
        $title        = "Редактировать landing page";
        if($id){
            $landingPage  = $this->index_model->getFromTableByParams(array('id' => $id), 'landing_page');
            $title        = "Редактировать landing page";
        }

        $contentArr         = $landingPage[0] ? $landingPage[0] : $this->landingPageArr;
        $url                = $this->index_model->prepareUrl($this->urlArr);
        $contentArr['url']  = $url;

        $this->data_arr     = array(
            'title'            => $title
        ,'content'          => $contentArr
        ,'menu_items'       => $this->edit_menu_model->childs
        ,'message'          => $this->message
        );

        $data = array(
            'menu'    => $this->load->view(MENU_ADMIN, '', true),
            'content' => $this->load->view('app-angular/landing/edit', $this->data_arr, true));

        $this->load->view('layout_admin', $data);
    }


    public function check_valid_landing()
    {
        $data           = $params = array();
        $id             = isset($_REQUEST['id']) && $_REQUEST['id'] ? $_REQUEST['id'] : null;
        $this->_formLandingValidation();
        $dataMain       = array(
            'unique'                => $_REQUEST['unique']
            ,'title'                => $_REQUEST['title']
            ,'title_description'    => $_REQUEST['title_description']
            ,'page_text'            => $_REQUEST['page_text']
            ,'letter_text'          => $_REQUEST['letter_text']);

        try{
            if($id){
                $params['id']  = $id;
                $data          = array_merge($dataMain, array(
                    'created_at'    => $_REQUEST['created_at'],
                    'status'        => $_REQUEST['status']));
                $this->_updateLanding($data, $params);
            } else {
                $data = array_merge($dataMain, array(
                    'created_at'    => date('Y-m-d H:i:s')
                    ,'status'       => STATUS_ON));

                $this->_addLanding($data);
            }
        } catch (Exception $e){
            $this->message = $e->getMessage();
            $this->landing_edit($id);
        }
    }


    private function _formLandingValidation()
    {
        $rules = array(
            array(
                'field'	=> 'unique',
                'label'	=> '<Ключ для ссылки>',
                'rules'	=> 'required'),
            array(
                'field'	=> 'page_text',
                'label'	=> '<Текст>',
                'rules'	=> 'required'),
            array(
                'field'	=> 'title',
                'label'	=> '<Название>',
                'rules'	=> 'required'));
        $this->form_validation->set_rules($rules);

        $isValid = $this->form_validation->run();
        Common::assertTrue($isValid, 'Форма заполнена неверно');
    }


    private function _addLanding($data)
    {
        $id = $this->index_model->addInTable($data, 'landing_page');
        Common::assertTrue($id, 'Информация не добавлена в базу');
        redirect('backend/landing');
    }


    private function _updateLanding($data, $params)
    {
        $isUpdated = $this->index_model->updateInTable($params['id'], $data, 'landing_page');
        Common::assertTrue($isUpdated, 'Not updated');
        redirect('backend/landing');
    }


    ////////////////////////////////LANDING article//////////////////////////
    public function landing_articles_list()
    {
        $title          = "Редактировать landing article";
        $landingList    = $this->index_model->getListFromTable('landing_articles');
        $landingsArr    = $this->index_model->getFromTableByParams(array('status' => STATUS_ON),'landing_page');

        foreach($landingList as $key => $articles){
            foreach($landingsArr as $i => $pages){
                $landingList[$key]['landing_page_name'] = ($pages['id'] == $articles['landing_page_id']) ? $pages['title'] : '';
            }
        }

        $this->data_arr     = array(
            'title'                 => $title
            ,'content'              => $landingList
            ,'menu_items'           => $this->edit_menu_model->childs
            ,'specMailerContainer'  => $this->load->view('app-angular/blocks/spec_mailer_form', array('landings' => $landingsArr), true)
        );

        $data = array(
            'menu'    => $this->load->view(MENU_ADMIN, '', true),
            'content' => $this->load->view('app-angular/landing_articles/show', $this->data_arr, true));

        $this->load->view('layout_admin', $data);
    }


    public function landing_articles_drop($id)
    {
        $this->index_model->delFromTable($id, 'landing_articles');
        redirect('backend/landing_articles');
    }


    public function landing_articles_edit($id = null)
    {
        $landingArticle  = null;
        $title        = "Создать landing article";
        if($id){
            $landingArticle  = $this->index_model->getFromTableByParams(array('id' => $id), 'landing_articles');
            $title        = "Редактировать landing article";
        }

        $landingsArr        = $this->index_model->getFromTableByParams(array('status' => STATUS_ON),'landing_page');
        $contentArr         = $landingArticle[0] ? $landingArticle[0] : $this->landingArticleArr;
        $url                = $this->index_model->prepareUrl($this->urlArr);
        $contentArr['url']  = $url;

        $this->data_arr     = array(
            'title'             => $title
            ,'content'          => $contentArr
            ,'landings'         => $landingsArr
            ,'menu_items'       => $this->edit_menu_model->childs
            ,'message'          => $this->message
        );

        $data = array(
            'menu'    => $this->load->view(MENU_ADMIN, '', true),
            'content' => $this->load->view('app-angular/landing_articles/edit', $this->data_arr, true));

        $this->load->view('layout_admin', $data);
    }


    public function check_valid_landing_articles()
    {
        $data           = $params = array();
        $id             = isset($_REQUEST['id']) && $_REQUEST['id'] ? $_REQUEST['id'] : null;

        try{
            $this->_formLandingArticlesValidation();
            $dataMain       = array(
                'title'             => $_REQUEST['title']
                ,'slug'             => $_REQUEST['slug']
                ,'text'             => $_REQUEST['text']
                ,'password_mp3'     => $_REQUEST['password_mp3']
                ,'link_mp3'         => $_REQUEST['link_mp3']
                ,'landing_page_id'  => $_REQUEST['landing']);

            if($id){
                $params['id']  = $id;
                $data          = array_merge($dataMain, array(
                    'created_at'    => $_REQUEST['created_at'],
                    'status'        => $_REQUEST['status']));
                $this->_updateLandingArticles($data, $params);
            } else {
                $data = array_merge($dataMain, array(
                    'created_at'    => date('Y-m-d H:i:s')
                    ,'status'       => STATUS_ON));

                $this->_addLandingArticles($data);
            }
        } catch (Exception $e){
            $this->message = $e->getMessage();
            $this->landing_articles_edit($id);
        }
    }


    private function _formLandingArticlesValidation()
    {
        $rules = array(
            array(
                'field'	=> 'slug',
                'label'	=> '<Alias названия>',
                'rules'	=> 'required'),
            array(
                'field'	=> 'title',
                'label'	=> '<Название>',
                'rules'	=> 'required'),
            array(
                'field'	=> 'landing',
                'label'	=> '<Landing page>',
                'rules'	=> 'required'));
        $this->form_validation->set_rules($rules);

        $isValid = $this->form_validation->run();
        Common::assertTrue($isValid, 'Форма заполнена неверно');
    }


    private function _addLandingArticles($data)
    {
        $id = $this->index_model->addInTable($data, 'landing_articles');
        Common::assertTrue($id, 'Информация не добавлена в базу');
        redirect('backend/landing_articles');
    }


    private function _updateLandingArticles($data, $params)
    {
        $isUpdated = $this->index_model->updateInTable($params['id'], $data, 'landing_articles');
        Common::assertTrue($isUpdated, 'Not updated');
        redirect('backend/landing_articles');
    }

}