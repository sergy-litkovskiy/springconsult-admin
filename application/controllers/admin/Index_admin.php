<?php
/**
 * @author Litkovskiy
 * @copyright 2010
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Index_admin extends CI_Controller
{
    public $arrMenu = array();
    public $gift = array();
    public $emptyAforizmusArr = array();
    public $defaultDescription = '';
    public $defaultKeywords = '';
    public $message;

    public function __construct()
    {
        parent::__construct();

        if (!$this->session->userdata('username') && !$this->session->userdata('loggedIn')) {
            $this->login_model->logOut();
        }
        $this->message = null;
        $this->arrMenu = $this->_prepareMenu();
        $this->result  = array("success" => null, "message" => null, "data" => null);

        $this->subscribeArr = array(
            'id'           => null
        , 'name' => null
        , 'label'    => null
        , 'image'       => null
        , 'material'  => null
        , 'status'         => null);

        $this->emptyAforizmusArr = array(
            'id'   => null
        , 'author' => null
        , 'text'   => null);


        $this->urlArr = explode('/', $_SERVER['REQUEST_URI']);
    }


    public function index()
    {
        $contentAndAssignArr = array();
        $this->data_menu     = array('menu' => $this->arrMenu);
        $contentArr          = $this->index_model->getNewsListAdmin();
        $landingsArr         = $this->index_model->getFromTableByParams(array('status' => STATUS_ON), 'landing_page');
        $contentAndAssignArr = $this->_prepareContentAndAssignsArr($contentArr);

        $this->data_arr = array(
            'title'             => 'Springconsulting - app-angular'
        , 'content'             => $contentAndAssignArr['content']
        , 'assigns'             => $contentAndAssignArr['assigns']
        , 'message'             => $this->message
        , 'specMailerContainer' => $this->load->view('app-angular/blocks/spec_mailer_form', array('landings' => $landingsArr), true)
        );

        $data = array(
            'menu'    => $this->load->view(MENU_ADMIN, $this->data_menu, true),
            'content' => $this->load->view('app-angular/index_admin/show', $this->data_arr, true));

        $this->load->view('layout_admin', $data);
    }


    public function show($slug)
    {
        $this->data_menu = array('menu' => $this->arrMenu);
        $contentArr      = $this->index_model->getContent($slug);

        $this->data_arr = array(
            'title' => 'Springconsult - edit ' . $slug
        , 'content' => $contentArr
        , 'message' => $this->message
        );

        $data = array(
            'menu'    => $this->load->view(MENU_ADMIN, $this->data_menu, true),
            'content' => $this->load->view('app-angular/index_admin/edit', $this->data_arr, true));

        $this->load->view('layout_admin', $data);
    }


////////////////////////SUBSCRIBE////////////////////////////////
    public function gift_list()
    {
        $this->data_menu = array('menu' => $this->arrMenu);
//        $contentArr      = $this->index_model->getGiftListAdmin();
        $giftListWithArticles      = $this->gift_model->getGiftListWithArticlesAdmin();
        $giftArticlesMap      = $this->_prepareGiftArticlesMap($giftListWithArticles);

        $this->data_arr  = array(
            'title' => 'Springconsulting - app-angular'
        , 'content' => $giftArticlesMap
        , 'message' => $this->message
        );

        $data = array(
            'menu'    => $this->load->view(MENU_ADMIN, $this->data_menu, true),
            'content' => $this->load->view('app-angular/gift/show', $this->data_arr, true));

        $this->load->view('layout_admin', $data);

    }

    private function _prepareGiftArticlesMap(array $giftListWithArticles)
    {
        $map = array();

        foreach ($giftListWithArticles as $giftData) {
            $map[ArrayHelper::arrayGet($giftData, 'id')]['data']          = $giftData;
            $map[ArrayHelper::arrayGet($giftData, 'id')]['articleList'][] = $giftData;
        }

        return $map;
    }

    public function gift_edit($id = null)
    {
        $subscribes = [0];
        $assignArticleList = [];
        $title      = "Добавить продукт";
        if ($id) {
            $subscribes = $this->index_model->getGiftListAdmin($id);
            $title      = "Редактировать бесплатный продукт";
        }

        $articleList = $this->blog_model->getList();

        $assignArticles = $this->blog_model->getAssignedArticleListByGiftId($id);

        foreach ($assignArticles as $assignArticle) {
            $assignArticleList[ArrayHelper::arrayGet($assignArticle, 'article_id')] = $assignArticle;
        }

        $contentArr        = $subscribes[0] ? $subscribes[0] : $this->subscribeArr;
        $url               = $this->index_model->prepareUrl($this->urlArr);
        $contentArr['url'] = $url;

        $this->data_arr = array(
            'title'    => $title,
        'content'    => $contentArr,
        'menu_items' => $this->arrMenu,
        'message'    => $this->message,
            'articleList' => $articleList,
            'assignArticles' => $assignArticleList
        );

        $data = array(
            'menu'    => $this->load->view(MENU_ADMIN, '', true),
            'content' => $this->load->view('app-angular/gift/edit', $this->data_arr, true));

        $this->load->view('layout_admin', $data);
    }


    public function check_valid_gift()
    {
        $data          = $params = array();
        $fileName      = $materialName = null;
        $imgUploadPath = './subscribe/';
//        $materialUploadPath = './subscribegift/';
        $id = isset($_REQUEST['id']) && $_REQUEST['id'] ? $_REQUEST['id'] : null;
        $assignedNewArticleIds = ArrayHelper::arrayGet($_REQUEST, 'new_article_id', []);
        $assignedOldArticleIds = ArrayHelper::arrayGet($_REQUEST, 'old_article_id', []);

        try {
            if ($_FILES['image']['size'] > 0) {
                $fileName = $this->index_model->tryUploadFile($_FILES['image'], $imgUploadPath);
            }
            //if($_FILES['material']['size'] > 0){
//                $materialName = $this->_tryUploadFile($_FILES['material'], $materialUploadPath);
//            }

            $validationRules = $this->_prepareRulesGiftValidation();
            $this->_formGiftValidation($validationRules);
            if ($id) {
                //$this->_formSubscribeValidation($validationRules);
                $params ['id'] = $id;
                $dataUpdate    = array('name' => $_REQUEST['name']
                , 'label'                         => $_REQUEST['label']
                , 'status'                              => $_REQUEST['status']
                , 'material'                       => $_REQUEST['material']);

                $data               = $fileName ? array_merge(array('image' => $fileName), $dataUpdate) : $dataUpdate;
                $params['img_file'] = $fileName && $_REQUEST['old_image'] ? $_REQUEST['old_image'] : null;

                // $data = $materialName ? array_merge(array('material' => $materialName), $data) : $data;
//                $params['material_file'] = $materialName && $_REQUEST['old_material'] ? $_REQUEST['old_material'] : null;
                if (count($assignedNewArticleIds)) {
                    $this->_assignProcess($assignedNewArticleIds, $assignedOldArticleIds, $id);
                }

                $this->_updateGift($data, $params);
            } else {
                Common::assertTrue($fileName, 'Не загружена картинка');
                //Common::assertTrue($materialName, 'Не загружен материал');

                $data = array('name' => $_REQUEST['name']
                , 'label'                => $_REQUEST['label']
                , 'status'                     => STATUS_ON
                , 'image'                   => $fileName
                , 'material'              => $_REQUEST['material']);

                $id = $this->_addGift($data);

                if (count($assignedNewArticleIds)) {
                    $this->_assignProcess($assignedNewArticleIds, $assignedOldArticleIds, $id);
                }

                redirect('backend/gift');
            }
        } catch (Exception $e) {
            $this->message = $e->getMessage();
            $this->gift_edit($id);
        }
    }

    private function _assignProcess($assignedNewArticleIds, $assignedOldArticleIds, $id)
    {
        $assignsArr = array(
            'newSourceIdArr'  => $assignedNewArticleIds,
            'oldSourceIdArr'  => $assignedOldArticleIds,
            'assignId'        => $id,
            'assignFieldName' => 'gift_id',
            'sourceFieldName' => 'article_id',
            'table'           => 'gift_article_assignment'
        );

        $this->assign_model->setAssignArr($assignsArr);
        $this->assign_model->addOrDeleteAssigns();
    }

    private function _formGiftValidation($rules)
    {
        $this->form_validation->set_rules($rules);

        $isValid = $this->form_validation->run();
        Common::assertTrue($isValid, 'Форма заполнена неверно');
    }


    private function _prepareRulesGiftValidation()
    {
        return array(
            array(
                'field' => 'name',
                'label' => '<Название продукта>',
                'rules' => 'required'),
            array(
                'field' => 'label',
                'label' => '<Описание>',
                'rules' => 'required'),
            array(
                'field' => 'material',
                'label' => '<Материал>',
                'rules' => 'required'));
    }


    private function _addGift($data)
    {
        return $this->index_model->addInTable($data, 'gift');
    }


    private function _updateGift($data, $params)
    {
        $isUpdated = $this->index_model->updateInTable($params['id'], $data, 'gift');
        Common::assertTrue($isUpdated, 'Not updated');
        if (isset($params['img_file']) && $params['img_file']) {
            unlink('./subscribe/' . $params['img_file']);
        }
        //if(isset($params['material_file']) && $params['material_file']){
//            unlink('./subscribegift/'.$params['material_file']);
//        }
        redirect('backend/gift');
    }


    public function gift_drop($id)
    {
        try {
            $this->index_model->dropWithFile($id, $_REQUEST['file'], 'gift');

            $assignArticles = $this->blog_model->getAssignedArticleListByGiftId($id);
            if ($assignArticles) {
                foreach ($assignArticles as $assignArticleData) {
                    $this->index_model->delFromTable(
                        ArrayHelper::arrayGet($assignArticleData, 'gift_article_assignment_id'),
                        'gift_article_assignment'
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

////////////////////////////////AFORIZMUS//////////////////////////
    public function aforizmus_list()
    {
        $title         = "Редактировать афоризмы";
        $aforizmusList = $this->index_model->getAforizmusList();
        $contentArr    = $aforizmusList ? $aforizmusList : $this->emptyAforizmusArr;

        $this->data_arr = array(
            'title'    => $title
        , 'content'    => $contentArr
        , 'menu_items' => $this->arrMenu
        );

        $data = array(
            'menu'    => $this->load->view(MENU_ADMIN, '', true),
            'content' => $this->load->view('app-angular/aforizmus/show', $this->data_arr, true));

        $this->load->view('layout_admin', $data);
    }


    public function aforizmus_drop($id)
    {
        try {
            $this->index_model->delFromTable($id, 'aforizmus');
            $this->result['success'] = true;
        } catch (Exception $e) {
            $this->result['message'] = $e->getMessage();
        }

        print json_encode($this->result);
        exit;
    }


    public function aforizmus_edit($id = null)
    {
        try {
            $data = array('author' => $_REQUEST['author']
            , 'text'               => $_REQUEST['text']);
            if ($id) {
                $resultUpdate = $this->index_model->updateInTable($id, $data, 'aforizmus');
                Common::assertTrue($resultUpdate, 'Ошибка! Информация не обновлена');
                $this->result['success'] = 'Информация успешно обновлена!';
            } else {
                $id = $this->index_model->addInTable($data, 'aforizmus');
                Common::assertTrue($id, 'Ошибка! Информация не добавлена');
                $this->result['success'] = 'Информация успешно добавлена!';
            }
        } catch (Exception $e) {
            $this->result['message'] = $e->getMessage();
        }

        print json_encode($this->result);
        exit;
    }


    public function ajax_send_spec_mailer()
    {
        $errLogData = array();
        $data       = array('theme' => $_REQUEST['theme']
        , 'text'                    => $_REQUEST['text']
        , 'article_id'             => $_REQUEST['articleId']
        , 'landing_page_id'         => $_REQUEST['landingPageId']);
        try {
            $this->_assertSpecMailerData($data);

            $recipientsSpecMailerArr = $this->landing_model->getLandingRegistredRecipients($data['landing_page_id']);
            Common::assertTrue(count($recipientsSpecMailerArr), 'Не найден ни один подписчик для отправки');

            $data['created_at']  = Common::getDateTime('Y-m-d H:i:s');
            $specMailerHistoryId = $this->index_model->addInTable($data, 'spec_mailer_history');
            Common::assertTrue($specMailerHistoryId, 'Ошибка! Информация об отправке писем по спецрассылке НЕ вставлена в БД');

            $data['article_title'] = $_REQUEST['articleTitle'];
            $data['article_link']   = $_REQUEST['isLanding'] ? base_url() . "landing_articles/" . $data['article_id'] : base_url() . "article/" . $data['article_id'];

            $sentMailCounter = $this->_sendSpecMailer($recipientsSpecMailerArr, $data);
            Common::assertTrue($sentMailCounter > 0, 'Ошибка! Не было отправлено ни одного письма');


            $this->result['data']    = 'Спецрассылка прошла успешно! <br>Можно идти пить чай:)';
            $this->result['success'] = true;
        } catch (Exception $e) {
            $this->result['message'] = $e->getMessage();

            $errLogData['resource_id'] = ERROR_SRC_SPEC_MAILER;
            $errLogData['text']        = $e->getMessage() . " - Название статьи: " . $_REQUEST['articleTitle'];
            $errLogData['created_at']  = Common::getDateTime('Y-m-d H:i:s');
            $this->index_model->addInTable($errLogData, 'error_log');
        }

        print json_encode($this->result);
        exit;
    }


    private function _assertSpecMailerData($data)
    {
        foreach ($data as $key => $item) {
            Common::assertTrue($item, 'Ошибка! Не заполнено поле ' . $key);
        }
    }


    private function _sendSpecMailer($recipientsSpecMailerArr, $data)
    {
        $sentMailCounter = 0;
        $errLogData      = array();
        foreach ($recipientsSpecMailerArr as $recipient) {
            try {
                $isSent = $this->mailer_model->sendSpecMailerEmail($recipient, $data);
                Common::assertTrue($isSent, 'Ошибка! Письмо для подписчика ' . $recipient['name'] . ' (' . $recipient['email'] . ') не было отправлено');
                $sentMailCounter++;
            } catch (Exception $e) {
                $errLogData['resource_id'] = ERROR_SRC_SPEC_MAILER;
                $errLogData['text']        = $e->getMessage() . " - Название статьи: " . $data['article_title'];
                $errLogData['created_at']  = Common::getDateTime('Y-m-d H:i:s');
                $this->index_model->addInTable($errLogData, 'error_log');
            }
        }

        return $sentMailCounter;
    }


    public function spec_mailer_statistics($landingPageId = null)
    {
        $title = "Статистика спец. рассылки";
        if ($landingPageId) {
            $specMailerHistoryArr = $this->landing_model->getSpecMailerStatistics($landingPageId);
            foreach ($specMailerHistoryArr as $key => $specMailerHistory) {
                $specMailerHistoryArr[$key]['registred_list'] = $this->landing_model->getLandingRegistredRecipients($specMailerHistory['landing_page_id'], $specMailerHistory['created_at']);
            }

            $this->data_arr = array(
                'title'    => $title
            , 'content'    => $specMailerHistoryArr
            , 'menu_items' => $this->arrMenu
            );

            $data = array(
                'menu'    => $this->load->view(MENU_ADMIN, '', true),
                'content' => $this->load->view('app-angular/landing/spec_mailer_statistics_show', $this->data_arr, true)
            );
        } else {
            $landingPageArr = $this->index_model->getListFromTable('landing_page');

            $this->data_arr = array(
                'title'    => $title
            , 'content'    => $landingPageArr
            , 'menu_items' => $this->arrMenu
            );

            $data = array(
                'menu'    => $this->load->view(MENU_ADMIN, '', true),
                'content' => $this->load->view('app-angular/landing/landing_page_list', $this->data_arr, true)
            );
        }

        $this->load->view('layout_admin', $data);
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


    public function ajax_change_is_top()
    {
        $data             = $arrData = array();
        $data['is_top']   = $_REQUEST['is_top'];
        $arrData['id']    = $_REQUEST['id'];
        $arrData['table'] = $_REQUEST['table'];
        try {
            $result = $this->index_model->updateInTable($arrData['id'], $data, $arrData['table']);
            Common::assertTrue($result, 'Ошибка! Статус is_top не был изменен');
            $this->result['success'] = true;
        } catch (Exception $e) {
            $this->result['message'] = $e->getMessage();
        }

        print json_encode($this->result);
        exit;
    }


    public function ajax_change_status()
    {
        $data             = $arrData = array();
        $data['status']   = $_REQUEST['status'];
        $arrData['id']    = $_REQUEST['id'];
        $arrData['table'] = $_REQUEST['table'];
        try {
            if ($_REQUEST['table'] == 'announcement') {
                $this->_updateStatusToZero();
            }
            $result = $this->_update_status($data, $arrData);
            Common::assertTrue($result, 'Ошибка! Статус не был изменен');
            $this->result['success'] = true;
        } catch (Exception $e) {
            $this->result['message'] = $e->getMessage();
        }

        print json_encode($this->result);
        exit;
    }


    private function _updateStatusToZero()
    {
        $this->index_model->updateStatusToZero();
    }


    public function ajax_get_available_tag()
    {
        $tagArr = $this->index_model->getAvailableTag();

        print json_encode($tagArr);
        exit;
    }


    private function _prepareMenu()
    {
        return $this->edit_menu_model->childs;
    }


    private function _update_status($data, $arrData)
    {
        return $this->index_model->updateInTable($arrData['id'], $data, $arrData['table']);
    }


    public function logout()
    {
        return $this->login_model->logOut();
    }

}