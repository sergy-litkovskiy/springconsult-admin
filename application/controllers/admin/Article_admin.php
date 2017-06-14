<?php
/**
 * @author Litkovskiy
 * @copyright 2012
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Article_admin extends CI_Controller
{
    public $emptyArticleArr = array();
    private $data = array();
    public $message;
    public $result;
    public $urlArr;
    private $currentDate;
    /** @var  Login_model */
    public $login_model;
    /** @var  Index_admin */
    public $index_model;
    /** @var  Edit_menu_model */
    public $edit_menu_model;
    /** @var  Tags_model */
    public $tags_model;
    /** @var  Assign_model */
    public $assign_model;
    /** @var  CI_Form_validation */
    public $form_validation;
    /** @var  Mailer_model */
    public $mailer_model;
    /** @var  CI_Session */
    public $session;

    public function __construct()
    {
        parent::__construct();

        if (!$this->session->userdata('username') && !$this->session->userdata('loggedIn')) {
            $this->login_model->logOut();
        }

        $this->emptyArticleArr = array(
            'id'             => null
        , 'slug'             => null
        , 'text'             => null
        , 'description'             => null
        , 'title'            => null
        , 'image'            => null
        , 'num_sequence'     => null
        , 'status'           => null
        , 'meta_description' => null
        , 'meta_keywords'    => null
        , 'is_sent_mail'     => null
        , 'date'             => Common::getDateTime('Y-m-d')
        , 'time'             => Common::getDateTime('H:i:s')
        );

        $this->result  = array("success" => null, "message" => null, "data" => null);
        $this->urlArr  = explode('/', $_SERVER['REQUEST_URI']);
        $this->message = null;
    }


    public function article_edit($id = null)
    {
        $contentItems   = array(0);
        $assignArticles = $assignTagArr = array();
        $title          = "Добавить статью";
        if ($id) {
            $contentItems = $this->index_model->getDetailContentAdmin($id);
            $assignArtArr = $this->index_model->getAssignArticlesByArticleIdAdmin($id);
            $assignTagArr = $this->index_model->getAssignTagArr($id, 'articles_tag', 'article_id');

            foreach ($assignArtArr as $assignArt) {
                $assignArticles[$assignArt['article_id']][] = $assignArt['menu_id'];
            }
            if ($assignArticles) {
                $assignArticles = $assignArticles[$id];
            }
            $title = "Редактировать статью";
        }

        $contentArr        = $contentItems[0] ? $contentItems[0] : $this->emptyArticleArr;
        $url               = $this->index_model->prepareUrl($this->urlArr);
        $contentArr['url'] = $url;

        $this->data = array(
            'title'         => $title
        , 'content'         => $contentArr
        , 'menu_items'      => $this->edit_menu_model->childs
        , 'assign_articles' => $assignArticles
        , 'assign_tag_arr'  => $assignTagArr
        , 'message'         => $this->message
        );

        $data = array(
            'menu'    => $this->load->view(MENU_ADMIN, '', true),
            'content' => $this->load->view('app-angular/index_admin/edit', $this->data, true));

        $this->load->view('layout_admin', $data);
    }


    public function check_valid_article()
    {
        $data            = $params = array();
        $id              = isset($_REQUEST['id']) && $_REQUEST['id'] ? $_REQUEST['id'] : null;
        $assignMenuIdArr = isset($_REQUEST['menu']) && $_REQUEST['menu'] ? $_REQUEST['menu'] : array();
        $oldAssignMenuId = isset($_REQUEST['old_assign_id']) && $_REQUEST['old_assign_id'] ? $_REQUEST['old_assign_id'] : array();
        $arrArticlesTag  = !empty($_REQUEST['tag']) ? $_REQUEST['tag'] : array();

        try {
            $this->_formValidation();
            $data = $this->_prepareArticleDataForAddUpdate($_REQUEST);

            if ($id) {
                $params ['id'] = $id;
                $dataUpdate    = array(
                    'num_sequence' => $_REQUEST['num_sequence']
                , 'status'         => $_REQUEST['status']
                , 'is_sent_mail'   => $_REQUEST['is_sent_mail']
                );

                $data = array_merge($data, $dataUpdate);

                if (count($assignMenuIdArr)) {
                    $this->_assignProcess($assignMenuIdArr, $oldAssignMenuId, $id);
                }

                if (count($arrArticlesTag)) {
                    /** @var Tags_model $this->tags_model */
                    $this->tags_model->tagProcess($arrArticlesTag, $id, 'articles_tag', 'article_id');
                }

                $this->_update($data, $params);
            } else {
                $dataAdd = array(
                    'num_sequence' => '0'
                , 'status'         => STATUS_ON
                , 'is_sent_mail'   => '0'
                );

                $data = array_merge($data, $dataAdd);

                $id = $this->_add($data);
                Common::assertTrue($id, 'Форма заполнена неверно');

                if (count($assignMenuIdArr)) {
                    $this->_assignProcess($assignMenuIdArr, $oldAssignMenuId, $id);
                }

                if (count($arrArticlesTag)) {
                    $this->tags_model->tagProcess($arrArticlesTag, $id, 'articles_tag', 'article_id');
                }

                redirect('backend/news');
            }

        } catch (Exception $e) {
            $this->article_edit($id);
        }
    }


    private function _assignProcess($assignMenuIdArr, $oldAssignMenuId, $id)
    {
        $assignsArr = array(
            'newSourceIdArr' => $assignMenuIdArr
        , 'oldSourceIdArr'   => $oldAssignMenuId
        , 'assignId'         => $id
        , 'assignFieldName'  => 'article_id'
        , 'sourceFieldName'  => 'menu_id'
        , 'table'            => 'menu_article_assignment');

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
                'field' => 'title',
                'label' => '<Название раздела>',
                'rules' => 'required')
        , array(
                'field' => 'slug',
                'label' => '<Алиас раздела>',
                'rules' => 'required')
        , array(
                'field' => 'text',
                'label' => '<Текст>',
                'rules' => 'required')
        , array(
                'field' => 'date',
                'label' => '<Дата>',
                'rules' => 'required'));
    }


    private function _prepareArticleDataForAddUpdate($request)
    {
        return array('meta_description' => $request['meta_description']
        , 'meta_keywords'               => $request['meta_keywords']
        , 'title'                       => $request['title']
        , 'description'                       => $request['description']
        , 'slug'                        => $request['slug']
        , 'text'                        => $request['text']
        , 'image'                        => $request['image']
        , 'date'                        => isset($request['date']) ? $request['date'] : Common::getDateTime('Y-m-d')
        , 'time'                        => isset($request['time']) ? $request['time'] : Common::getDateTime('H:i:s'));
    }


    public function ajax_send_article_to_subscribers()
    {
        $errLogData = array();
        $articleId  = $_REQUEST['article_id'] && is_numeric($_REQUEST['article_id']) ? $_REQUEST['article_id'] : null;
        try {
            Common::assertTrue($articleId, 'Ошибка! Не установлен идентификатор статьи');
            $articlesArr   = $this->index_model->getDetailContent($articleId);
            $articleDetail = count($articlesArr) ? $articlesArr[0] : null;
            Common::assertTrue($articleDetail, 'Ошибка! Нет данных по запрашиваемой статье');

            $recipientsArr = $this->index_model->getNlSubscribers();
            Common::assertTrue(count($recipientsArr), 'Не найден ни один подписчик для отправки');

//            $sentMailCounter = $this->_sendNlSubscribe($recipientsArr, $articleDetail);
//            Common::assertTrue($sentMailCounter > 0, 'Ошибка! Не было отправлено ни одного письма');
            $this->_unisenderCreateEmailMessage($articleDetail);

            $isUpdated = $this->_updateArticleStatusIsMailSent($articleDetail['id']);
            Common::assertTrue($isUpdated, 'Ошибка! Статус сатьи не был изменен на is mail sent');
            $this->result['success'] = true;
        } catch (Exception $e) {
            $this->result['message'] = $e->getMessage();

            $errLogData['resource_id'] = ERROR_SRC_ARTICLE_MAILER;
            $errLogData['text']        = $e->getMessage() . " - Название статьи: " . $articleDetail['title'];
            $errLogData['created_at']  = Common::getDateTime('Y-m-d H:i:s');
            $this->index_model->addInTable($errLogData, 'error_log');
        }

        print json_encode($this->result);
        exit;
    }


//    private function _sendNlSubscribe($recipientsArr, $articleDetail)
//    {
//        $sentMailCounter = 0;
//         foreach($recipientsArr as $recipient){
//             try{
//                $unsubscribeLink = $this->index_model->unsubscribeHashProcess($recipient['id']);
//                Common::assertTrue($unsubscribeLink, 'Ошибка! Не был сформирован url для отказа от подписки');
//
//                $data = array('article_id'    => $articleDetail['id']
//                            ,'recipients_id'   => $recipient['id']
//                            ,'date'            => date('Y-m-d')
//                            ,'time'            => date('H:i:s'));
//
//                $historyId = $this->index_model->addInTable($data, 'articles_subscribe_mail_history');
//                Common::assertTrue($historyId, 'Ошибка! Не произошла запись в articles_subscribe_mail_history');
//
//                $isSent = $this->mailer_model->sendArticlesSubscribedEmail($recipient, $articleDetail, $unsubscribeLink);
//                Common::assertTrue($isSent, 'Ошибка! Письмо для подписчика '.$recipient['name'].' ('.$recipient['email'].') не было отправлено');
//
//                $sentMailCounter++;
//            } catch (Exception $e){
//                $errLogData['resource_id']  = ERROR_SRC_ARTICLE_MAILER;
//                $errLogData['text']         = $e->getMessage()." - Название статьи: ".$articleDetail['title'];
//                $errLogData['created_at']   = date('Y-m-d H:i:s');
//                $this->index_model->addInTable($errLogData, 'error_log');
//            }
//         }
//
//         return $sentMailCounter;
//    }


    private function _unisenderCreateEmailMessage($articleDetail)
    {
        $jsonObj = null;
        $postArr = array(
            'api_key'      => UNISENDERAPIKEY,
            'sender_name'  => SITE_TITLE,
            'sender_email' => ADMIN_EMAIL,
            'subject'      => $articleDetail['title'],
            'wrap_type'    => 'left',
            'list_id'      => UNISENDERMAINLISTID,
            'body'         => $this->mailer_model->getUnisenderSubscribeEmailTpl($articleDetail)
        );

        $result = startCurlExec($postArr, 'http://api.unisender.com/ru/api/createEmailMessage?format=json');
        Common::assertTrue($result, 'Ошибка! Unisender API(createEmailMessage) не отвечает!');

        $jsonObj = json_decode($result);
        Common::assertTrue($jsonObj, 'Ошибка! API(createEmailMessage) Invalid JSON');
//Common::debugLogProd('_unisenderCreateEmailMessage');        
//Common::debugLogProd($jsonObj);
        if ((isset($jsonObj->error) && is_object($jsonObj->error)) && (isset($jsonObj->code) && is_object($jsonObj->code))) {
            throw new Exception("An error occured: " . @$jsonObj->error . "(code: " . @$jsonObj->code . ")");
        } else {
            return $this->_unusenderCreateCampaign($jsonObj->result->message_id);
        }
    }


    private function _unusenderCreateCampaign($messageId)
    {
        $postArr = array(
            'api_key'     => UNISENDERAPIKEY,
            'message_id'  => $messageId,
            'track_read'  => '0',
            'track_links' => '0'
        );

        $result = startCurlExec($postArr, 'http://api.unisender.com/ru/api/createCampaign?format=json');
        Common::assertTrue($result, 'Ошибка! Unisender API(createCampaign) не отвечает!');

        $jsonObj = json_decode($result);
        Common::assertTrue($jsonObj, 'Ошибка! API(createCampaign) Invalid JSON');
//Common::debugLogProd('_unusenderCreateCampaign');
//Common::debugLogProd($jsonObj);         
        if ((isset($jsonObj->error) && $jsonObj->error !== '') && isset($jsonObj->code)) {
            throw new Exception("An error occured: " . @$jsonObj->error . "(code: " . @$jsonObj->code . ")");
        } else {
            //return $this->result['data'] = "Рассылка на Unisender запущена успешно!";
            return $this->result['data'] = "Рассылка на Unisender<br> запущена успешно со статусом: " . @$jsonObj->result->status . "!";
        }
    }


    private function _updateArticleStatusIsMailSent($articleId)
    {
        return $this->index_model->updateInTable($articleId, array('is_sent_mail' => STATUS_ON), 'article');
    }


    private function _add($data)
    {
        return $this->index_model->addInTable($data, 'article');
    }


    private function _update($data, $params)
    {
        if ($this->index_model->updateInTable($params['id'], $data, 'article')) {
            redirect('backend/news');
        } else {
            throw new Exception('Not updated');
        }
    }


    public function drop($id)
    {
        try {
            $this->index_model->delFromTable($id, 'article');
            $assignArticlesArr = $this->index_model->getFromTableByParams(array('article_id' => $id), 'menu_article_assignment');

            if (count($assignArticlesArr)) {
                foreach ($assignArticlesArr as $assignArticles) {
                    $this->index_model->delFromTable($assignArticles['id'], 'menu_article_assignment');
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