<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Gift extends MY_Controller
{
    /** @var Gift_model */
    public $gift_model;

    public function ajaxGetGiftList()
    {
        $giftList = $this->gift_model->getGiftListWithArticles();

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['data' => $giftList]));
    }

    public function ajaxGiftSubscribe()
    {
        try {
            if (!$data = json_decode(file_get_contents('php://input'), true)) {
                throw new Exception('Subscribe form is not filled');
            }

            if ($this->validateSubscribeData($data)) {
                $result = $this->trySubscribeProcess($data);
            }

        } catch (Exception $e) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(400, $e->getMessage());
        }

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($result));
    }

    private function validateSubscribeData($data)
    {
        if (!$giftId = ArrayHelper::arrayGet($data, 'giftId')) {
            throw new Exception('form');
        }

        if (!$giftName = ArrayHelper::arrayGet($data, 'giftName')) {
            throw new Exception('form');
        }

        if (!$userName = ArrayHelper::arrayGet($data, 'userName')) {
            throw new Exception('userName|required');
        }

        if (!$email = ArrayHelper::arrayGet($data, 'email')) {
            throw new Exception('email|required');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('email|pattern');
        }

        return true;
    }

    private function trySubscribeProcess($data)
    {
        $recipientCandidateData = [
            'name' => ArrayHelper::arrayGet($data, 'userName'),
            'email' => ArrayHelper::arrayGet($data, 'email'),
            'confirmed' => STATUS_ON,
            'created_at' => date('Y-m-d H:i:s'),
        ];

        $recipientData = $this->recipient_model->getRecipientData($recipientCandidateData);

        if (!$recipientId = ArrayHelper::arrayGet($recipientData, 'id')) {
            throw new Exception(
                'К сожалению, при регистрации произошла ошибка. Пожалуйста, попробуйте еще раз'
            );
        }

        $subscribeName = ArrayHelper::arrayGet($data, 'giftName', '');
        $subscribeId = ArrayHelper::arrayGet($data, 'giftId', 0);

        $recipientData['subscribe']['name'] = trim(strip_tags($subscribeName));
        $recipientData['subscribe']['id']   = $subscribeId;

        if (!$hashData = $this->linkspacker_model->hashProcess($recipientData, $recipientId)) {
            throw new Exception(
                'К сожалению, при регистрации произошла ошибка. Пожалуйста, попробуйте еще раз'
            );
        }

        $this->mailer_model->sendAdminSubscribeEmailMessage($recipientData);
        $this->_tryAddMailHistory($recipientData);

        return ['data' => ArrayHelper::arrayGet($hashData, 'url')];
    }

    protected function _tryAddMailHistory($recipientData)
    {
        $dataMailHistory = [
            'subscribe_id'  => ArrayHelper::arrayGet($recipientData, 'subscribe.id'),
            'recipients_id' => ArrayHelper::arrayGet($recipientData, 'id'),
            'date' => date('Y-m-d'),
            'time' => date('H:i:s')
        ];

        $this->index_model->addInTable($dataMailHistory, 'mail_history');
    }

//    protected function _subscribeMailProcess($data, $recipientDataArr, $hashLink)
//    {
//        $this->_trySendSubscribeMail($data, $recipientDataArr, $hashLink);
//        try {
//            $this->_trySendSubscribeAdminMail($data);
//            $this->_tryAddMailHistory($data, $recipientDataArr);
//        } catch (Exception $e) {
//            $this->mailer_model->sendAdminErrorEmailMessage($e->getMessage());
//        }
//    }
//
//
//    protected function _trySendSubscribeMail($data, $recipientDataArr, $hashLink)
//    {
//        $messId = ArrayHelper::arrayGet($data, 'subscribe_id') ?
//            $this->mailer_model->sendFreeProductSubscribeEmailMessage($data, $recipientDataArr, $hashLink) :
//            $this->mailer_model->sendArticleSubscribeConfirmationEmailMessage($recipientDataArr, $hashLink);
//
//        Common::assertTrue($messId, "<p class='error'>К сожалению, письмо с сылкой на материал не было отправлено.<br/>Пожалуйста, попробуйте еще раз</p>");
//
//        $this->result['success'] = true;
//        $this->result["data"]    = "<p class='success'>Спасибо за подписку!<br>На Ваш e-mail отправлено письмо для подтверждения вашей подписки. Проверьте Ваш почтовый ящик - папку Входящие и СПАМ.</p>";
//    }


//    public function showFinishSubscribe($finishSubscribeProcessDataArr)
//    {
//        $this->dataMenu          = array('menu' => $this->arrMenu, 'current_url' => $this->urlArr[count($this->urlArr) - 1]);
//        $recipientData           = $this->index_model->getRecipientById($finishSubscribeProcessDataArr['recipient_id']);
//        $subscribeId             = ArrayHelper::arrayGet($finishSubscribeProcessDataArr, 'subscribe_id');
//        $finishSubscribeTemplate = $subscribeId > 0 ? 'index/finish_free_product_subscribe' : 'index/finish_articles_subscribe';
//
//        $this->data = array(
//            'title'              => SITE_TITLE . ' - subscribe'
//            , 'aforizmus'        => $this->aforizmus
//            , 'meta_keywords'    => DEFAULT_META_KEYWORDS
//            , 'meta_description' => DEFAULT_META_DESCRIPTION
//            , 'recipient_data'   => $recipientData
//            , 'url'              => ArrayHelper::arrayGet($finishSubscribeProcessDataArr, 'url')
//        );
//
//        $data = array(
//            'menu'      => $this->load->view(MENU, $this->dataMenu, true),
//            'content'   => $this->load->view($finishSubscribeTemplate, $this->data, true),
//            'cloud_tag' => $this->load->view('blocks/cloud_tag', $this->cloudsTag, true),
//            'subscribe' => $this->load->view('blocks/subscribe', count($this->subscribe) ? $this->subscribe : null, true));
//        $this->load->view('layout', $data);
//    }

//    private function finishSubscribeProcess($recipientData)
//    {
//        $hash = ArrayHelper::arrayGet($recipientData, 'hashData.hash');
//
//        $hash     = Arr;
//
//        $linksPackerData = $this->linkspacker_model->getOneByParams(['hash' => $hash]);
//        Common::assertTrue($linksPackerData, "");
//
//        $url         = ArrayHelper::arrayGet($linksPackerData, 'url');
//        $linkId      = ArrayHelper::arrayGet($linksPackerData, 'id');
//        $count       = ArrayHelper::arrayGet($linksPackerData, 'count');
//        $subscribeId = ArrayHelper::arrayGet($linksPackerData, 'subscribe_id');
//
//        $updateData = ['count' => $count + 1, 'updated_at' => date('Y-m-d H:i:s')];
//        $this->index_model->updateInTable($linkId, $updateData, 'links_packer');
//
//        $urlParts            = explode('/', $url);
//        $recipientId         = $urlParts[count($urlParts) - 1];
//
//        return (['url' => $url, 'subscribe_id' => $subscribeId, 'recipient_id' => $recipientId]);
//    }

    public function outputSubscribe($giftId, $recipientId)
    {
        try {
            Common::assertTrue($giftId, "");
            $subscribeDataArr = $this->gift_model->getOneByParams(['id' => $giftId]);
            $recipient = $this->recipient_model->getOneByParams(['id' => $recipientId]);

            Common::assertTrue($recipient, "");
            Common::assertTrue($subscribeDataArr, "");

            $this->_outputFile(ArrayHelper::arrayGet($subscribeDataArr, 'material'));
        } catch (Exception $e) {
            redirect('/index');
        }
    }

    private function _outputFile($fileName)
    {
        $filePath = './subscribegift/' . $fileName;

        if (!file_exists($filePath)) {
            redirect('/index');
        }

        header("Content-Type: application/octet-stream");
        header("Accept-Ranges: bytes");
        header("Content-Length: " . filesize($filePath));
        header("Content-Disposition: attachment; filename=" . $fileName);
        readfile($filePath);
    }
}