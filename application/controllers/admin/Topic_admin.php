<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Topic_admin extends CI_Controller
{
    public  $emptyTopicList = array();
    private $data           = array();
    public  $message;
    public  $result;
    public  $urlArr;

    /** @var  Index_admin */
    public $index_model;
    /** @var  Login_model */
    public $login_model;
    /** @var  Topic_model */
    public $topic_model;
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

        $this->emptyTopicList = array(
            'id'     => null,
            'name'   => null,
            'slug'   => null,
            'status' => null
        );

        $this->result  = array("success" => null, "message" => null, "data" => null);
        $this->urlArr  = explode('/', $_SERVER['REQUEST_URI']);
        $this->message = null;
    }

    public function index()
    {
        $topicListWithArticles = $this->topic_model->getTopicListWithArticlesAdmin();
        $topicArticlesMap      = $this->_prepareTopicArticlesMap($topicListWithArticles);

        $contentData = array(
            'title'            => 'Springconsulting - app-angular',
            'topicArticlesMap' => $topicArticlesMap,
            'message'          => $this->message
        );

        $data = array(
            'menu'    => $this->load->view(MENU_ADMIN, '', true),
            'content' => $this->load->view('app-angular/topic/show', $contentData, true));

        $this->load->view('layout_admin', $data);
    }

    private function _prepareTopicArticlesMap(array $topicListWithArticles)
    {
        $map = array();

        foreach ($topicListWithArticles as $topicData) {
            $map[ArrayHelper::arrayGet($topicData, 'id')]['data']          = $topicData;
            $map[ArrayHelper::arrayGet($topicData, 'id')]['articleList'][] = $topicData;
        }

        return $map;
    }

    public function edit($id = null)
    {
        $topicData      = array();
        $assignArticleList = array();
        $title          = "Добавить topic";

        $articleList = $this->index_model->getListFromTable('article');

        if ($id) {
            $topicData      = $this->topic_model->getListByParams(['id' => $id]);
            $assignArticles = $this->blog_model->getAssignedArticleListByTopicId($id);

            foreach ($assignArticles as $assignArticle) {
                $assignArticleList[ArrayHelper::arrayGet($assignArticle, 'article_id')] = $assignArticle;
            }

            $title = "Редактировать topic";
        }

        $content        = ArrayHelper::arrayGet($topicData, 0, $this->emptyTopicList);
        $url            = $this->index_model->prepareUrl($this->urlArr);
        $content['url'] = $url;

        $this->data = array(
            'title'          => $title,
            'content'        => $content,
            'menu_items'     => $this->edit_menu_model->childs,
            'assignArticles' => $assignArticleList,
            'articleList'    => $articleList,
            'message'        => $this->message
        );

        $data = array(
            'menu'    => $this->load->view(MENU_ADMIN, '', true),
            'content' => $this->load->view('app-angular/topic/edit', $this->data, true));

        $this->load->view('layout_admin', $data);
    }


    public function save()
    {
        $data            = $params = array();
        $id              = ArrayHelper::arrayGet($_REQUEST, 'id');
        $assignedNewArticleIds = ArrayHelper::arrayGet($_REQUEST, 'new_article_id', array());
        $assignedOldArticleIds = ArrayHelper::arrayGet($_REQUEST, 'old_article_id', array());

        try {
            $this->_formValidation();
            $data['name'] = ArrayHelper::arrayGet($_REQUEST, 'name');
            $data['slug'] = ArrayHelper::arrayGet($_REQUEST, 'slug');

            if ($id) {
                $params['id'] = $id;
                $dataUpdate    = array('status' => ArrayHelper::arrayGet($_REQUEST, 'status'));

                $data = array_merge($data, $dataUpdate);

                if (count($assignedNewArticleIds)) {
                    $this->_assignProcess($assignedNewArticleIds, $assignedOldArticleIds, $id);
                }

                $this->_update($data, $params);
            } else {
                $dataAdd = array('status' => STATUS_ON);

                $data = array_merge($data, $dataAdd);

                $id = $this->_add($data);
                Common::assertTrue($id, 'Форма заполнена неверно');

                if (count($assignedNewArticleIds)) {
                    $this->_assignProcess($assignedNewArticleIds, $assignedOldArticleIds, $id);
                }

                redirect('backend/topic');
            }

        } catch (Exception $e) {
            $this->edit($id);
        }
    }

    private function _assignProcess($assignedNewArticleIds, $assignedOldArticleIds, $id)
    {
        $assignsArr = array(
            'newSourceIdArr'  => $assignedNewArticleIds,
            'oldSourceIdArr'  => $assignedOldArticleIds,
            'assignId'        => $id,
            'assignFieldName' => 'topic_id',
            'sourceFieldName' => 'article_id',
            'table'           => 'topic_article_assignment'
        );

        $this->assign_model->setAssignArr($assignsArr);
        $this->assign_model->addOrDeleteAssigns();
    }

    private function _formValidation()
    {
        $rules = $this->_prepareArticleValidationRules();
        $this->form_validation->set_rules($rules);

        $isValid = $this->form_validation->run();
        Common::assertTrue($isValid, 'Форма заполнена неверно');
    }

    private function _prepareArticleValidationRules()
    {
        return array(
            array(
                'field' => 'name',
                'label' => '<Название темы>',
                'rules' => 'required'));
    }

    private function _add($data)
    {
        return $this->index_model->addInTable($data, 'topic');
    }

    private function _update($data, $params)
    {
        if ($this->index_model->updateInTable(ArrayHelper::arrayGet($params, 'id'), $data, 'topic')) {
            redirect('backend/topic');
        } else {
            throw new Exception('Not updated');
        }
    }

    public function drop($id)
    {
        try {
            $this->topic_model->delFromTable($id, 'topic');
            $assignArticles = $this->blog_model->getAssignedArticleListByTopicId($id);

            if ($assignArticles) {
                foreach ($assignArticles as $assignArticleData) {
                    $this->index_model->delFromTable(
                        ArrayHelper::arrayGet($assignArticleData, 'topic_article_assignment_id'),
                        'topic_article_assignment'
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