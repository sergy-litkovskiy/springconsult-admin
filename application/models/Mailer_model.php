<?php
/**
 * @author Litkovsky
 * @copyright 2010
 * model for index page
 */
 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mailer_model extends Crud
{

    public function __construct()
    {
        parent::__construct();
    }


    public function sendAdminSubscribeEmailMessage($data)
    {
        $message = sprintf(
            "Type of message: Free product subscribe action (%s)<br/>\r\n
            Date: %s / Time %s<br/>\r\n
            Subscriber: %s (email of author : %s)\r\n",
            ArrayHelper::arrayGet($data, 'subscribe.name'),
            date('Y-m-d'),
            date('H:i:s'),
            ArrayHelper::arrayGet($data, 'name'),
            ArrayHelper::arrayGet($data, 'email')
        );

        return $this->_sendAdminEmailMessage($message);
    }


    public function sendAdminErrorEmailMessage($errorMess)
    {
        $message = "Type of message: 'Error message'<br/>\r\n
                    Date: ".date('Y-m-d')." / Time ".date('H:i:s')."<br/>\r\n
                    Message error: ".$errorMess.")<br/>\r\n";

        return $this->_sendAdminEmailMessage($message);
    }


    public function sendEmailMessage($data)
    {
        $message = "Type of message: 'Message from contact form'<br/>\r\n
                    Date: ".date('Y-m-d')." / Time ".date('H:i:s')."<br/>\r\n
                    Message from: ".$data['name']." (email of author :".$data['email'].").<br/>\r\n
                    Message: ".@$data['text'].".\r\n";

        return $this->_sendAdminEmailMessage($message);
    }

    public function sendAdminSaleEmailMessage($data)
    {
        $message = sprintf(
            "Type of message: 'Message from shop page'<br/>\r\n
            Date: %s / Time %s<br/>\r\n
            Product name: %s (%s)<br/>\r\n
            Product price: %s<br/>\r\n
            Buyer name: %s<br/>\r\n
            Buyer email: %s<br/>\r\n
            Buyer email: %s<br/>\r\n",
            date('Y-m-d'),
            date('H:i:s'),
            ArrayHelper::arrayGet($data, 'title'),
            ArrayHelper::arrayGet($data, 'productId'),
            ArrayHelper::arrayGet($data, 'price'),
            ArrayHelper::arrayGet($data, 'name'),
            ArrayHelper::arrayGet($data, 'email'),
            ArrayHelper::arrayGet($data, 'phone')
        );

        return $this->_sendAdminEmailMessage($message);
    }

    private function _sendAdminEmailMessage($message)
    {
        $headers    = $this->_getMailHeader();
        $email      = SUPERADMIN_EMAIL;
        $subject    = "Message from Springconsulting site for admin";
        $isMailSent = mail($email, $subject, $message, $headers);

        return $isMailSent;
    }


    public function sendLandingSubscribeEmailMessage($landingPageData, $recipientDataArr)
    {
        $headers    = $this->_getMailHeader();
        $email      = $recipientDataArr['email'];
        $subject    = "Ваша регистрация на '".$landingPageData['title']."'";
        $body       = "<p>Добрый день, ".$recipientDataArr['name']."!</p>";
        $body      .= $landingPageData['letter_text'];
        $message    = $this->_getEmailTamplate($body);

        $isMailSent = mail($email, $subject, $message, $headers);

        return $isMailSent;
    }


    public function sendFreeProductSubscribeEmailMessage($data, $recipientDataArr, $hashLink)
    {
        $headers    = $this->_getMailHeader();
        $email      = $recipientDataArr['email'];
        $subject    = "Подписка на бесплатный продукт от Springconsulting";
        $body       = '<p><b>Здравствуйте, '.$recipientDataArr['name'].'!</b></p>

                        <p>На Ваш email '.date("d-m-Y").' была оформлена подписка на получение бесплатного доступа к следующим материалам : < <b>'.$data['subscribe_name'].'</b> >(Автор: Литковская Елена, Spring Consulting)</p>

						<p> Чтобы скачать бесплатный материал, пожалуйста, перейдите по ссылке – <a href="'.$hashLink.'">'.$hashLink.'</a></p>

                        <p>В случае, если Вы НЕ подписывались на получение указанного материала, то просто не реагируйте на это письмо и Ваш email-адрес автоматически будет исключен из списка рассылки.</p>

                        <p>С наилучшими пожеланиями,</p>
                        <p>Команда Spring Consulting</p>';
        $message    = $this->_getEmailTamplate($body);

        $isMailSent = mail($email, $subject, $message, $headers);

        return $isMailSent;
    }


    public function sendArticleSubscribeConfirmationEmailMessage($recipientDataArr, $hashLink)
    {
        $headers    = $this->_getMailHeader();
        $email      = $recipientDataArr['email'];
        $subject    = "Подписка на получение новых статей от Springconsulting";
        $body       = '<p><b>Здравствуйте, '.$recipientDataArr['name'].'!</b></p>

                        <p>На Ваш email '.date("d-m-Y").' была оформлена подписка на получение новых статей по личной эффективности от коуча <a href="'.base_url().'show/about_me">Литковской Елены</a>.</p>

                        <p> Подтвердите подписку на получение новых статей, перейдя по ссылке – <a href="'.$hashLink.'">'.$hashLink.'</a></p>

                        <p>В случае, если Вы НЕ подписывались на получение указанных материалов, то просто не реагируйте на это письмо, и Ваш email-адрес автоматически будет исключен из списка рассылки.</p>

                        <p>С наилучшими пожеланиями,</p>
                        <p>Команда <a href="'.base_url().'">Spring Consulting</a></p>';
        $message    = $this->_getEmailTamplate($body);

        $isMailSent = mail($email, $subject, $message, $headers);

        return $isMailSent;
    }


    public function sendArticlesSubscribedEmail($recipient, $articleDetail, $unsubscribeLink)
    {
        $headers    = $this->_getMailHeader();
        $email      = $recipient['email'];
        $subject    = $articleDetail['title'];
        $body       = '<p><b>Добрый день, '.$recipient['name'].'!</b></p>

                        <p>Для вас новая статья <b>"'.$articleDetail['title'].'"</b> на сайте "Spring Сonsulting",<br>
                        читайте здесь: <a href="'.base_url().'article/'.$articleDetail['id'].'">'.base_url().'article/'.$articleDetail['id'].'</a></p>

                        <p><i><a style="color:#58753E; text-decoration: none; " href="'.base_url().'article/'.$articleDetail['id'].'">'.Common::cutString($articleDetail['text'], 100).'</a></i></p>

                        <p>Продолжение читайте здесь: <a href="'.base_url().'article/'.$articleDetail['id'].'">'.base_url().'article/'.$articleDetail['id'].'</a></p>

                        <p>Приятного вам чтения!</p>

                        <p>Литковская Елена и команда <a href="'.base_url().'">Spring Consulting</a></p>
                        <hr>
                        <p style="font-size:8pt">Вы получили это письмо в рамках рассылки компании  "Spring Сonsulting".
                        Если по определенным причинам Вы не желаете в дальнейшем получать от нас информационные сообщения, вы можете отписаться от рассылки <a style="color:blue" href="'.$unsubscribeLink.'">'.$unsubscribeLink.'</a>.
                        </p>';
        $message    = $this->_getEmailTamplate($body);

        $isMailSent = mail($email, $subject, $message, $headers);

        return $isMailSent;
    }


    public function getUnisenderSubscribeEmailTpl($articleDetail)
    {
        $baseUrl = base_url().'logo_top.png';
        return '
            <table border="0" cellpadding="0" cellspacing="0" width="650" align="left">
                    <tr>
                        <td style="background:#D8FDB7; vertical-align:top; width: 66px">
                            <img width="66" src="'.$baseUrl.'" />
                        </td>
                        <td style="padding: 1px 1px 5px 10px;; vertical-align:top">
                            <p><b>Добрый день, {{Name}}!</b></p>

                            <p>Для вас новая статья <b>"'.$articleDetail['title'].'"</b> на сайте "Spring Сonsulting",<br>
                            читайте здесь: <a href="'.base_url().'article/'.$articleDetail['id'].'">'.base_url().'article/'.$articleDetail['id'].'</a></p>

                            <p><i><a style="color:#58753E; text-decoration: none; " href="'.base_url().'article/'.$articleDetail['id'].'">'.Common::cutString($articleDetail['text'], 100).'</a></i></p>

                            <p>Продолжение читайте здесь: <a href="'.base_url().'article/'.$articleDetail['id'].'">'.base_url().'article/'.$articleDetail['id'].'</a></p>

                            <p>Приятного вам чтения!</p>

                            <p>Литковская Елена и команда <a href="'.base_url().'">Spring Consulting</a></p>
                            <hr>
                            <p style="font-size:8pt">Вы получили это письмо в рамках рассылки компании  "Spring Сonsulting".
                            Если по определенным причинам Вы не желаете в дальнейшем получать от нас информационные сообщения, вы можете отписаться от рассылки перейдя по ссылке ниже.
                            </p>
                        </td>
                    </tr>
            </table>';
    }



    private function _getEmailTamplate($body)
    {
        return '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                        <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru">
                        <head>
                            <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
                            <title>SpringConsult</title>
                        </head>
                        <body>
                            <table border="0" cellpadding="0" cellspacing="0" width="650" align="center">
                                <tr>
                                    <td style="background:#D8FDB7; vertical-align:top; width: 66px">
                                        <img width="66" src="'.base_url().'logo_top.png" alt="SpringConsult" />
                                    </td>
                                    <td style="padding: 1px 1px 5px 10px;; vertical-align:top">
                                        '.$body.'
                                    </td>
                                 </tr>
                            </table>
                        </body>
                            <style type="text/css">
                                body {
                                    margin: 0;
                                    background: #fff;
                                    font-size: 14px;
                                }
                                p { margin-bottom: 16px; font-size: 10pt; color:#4E4E4E}
                                a {color:red; font-size:10pt}
                                a:hover { text-decoration: none; }
                                td {height:250; vertical-align: top}
                                table {border: solid 1px #B4D795;}
                            </style>
                        </html>';
    }



    private function _getMailHeader()
    {
        $headers  = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=utf-8\r\n";
        $headers .= "From: helen.springconsulting@gmail.com \r\n";

        return $headers;

    }
    
    
    public function sendSpecMailerEmail($recipientDataArr, $data)
    {
        $headers    = $this->_getMailHeader();
        $email      = $recipientDataArr['email'];
        $subject    = $data['theme'];
        $body       = "<p><b>Здравствуйте, ".$recipientDataArr['name']."!</b></p>
                       ".$data['text']."   
                        <p>Читайте подробнее в статье <b>'".$data['article_title']."'</b> <br/>на сайте Spring Сonsulting: <a href='".$data['article_link']."'>".$data['article_link']."</a></p>
                        <p>С наилучшими пожеланиями,</p>
                        <p>Команда <a href='".base_url()."'>Spring Consulting</a></p>";
        $message    = $this->_getEmailTamplate($body);
        $isMailSent = mail($email, $subject, $message, $headers);

        return $isMailSent;
    }


    public function sendSaleMailerEmail($recipientDataArr, $saleProductsLettersData)
    {
        $headers    = $this->_getMailHeader();
        $email      = $recipientDataArr['email'];
        $subject    = $saleProductsLettersData['subject'];
        $body       = "<p><b>Здравствуйте, ".$recipientDataArr['name']."!</b></p>
                       ".$saleProductsLettersData['text'];
        $message    = $this->_getEmailTamplate($body);
        $isMailSent = mail($email, $subject, $message, $headers);

        return $isMailSent;
    }

}