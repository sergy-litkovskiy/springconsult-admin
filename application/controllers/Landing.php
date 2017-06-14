<?php
/**
 * @author Litkovskiy
 * @copyright 2010
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Landing extends CI_Controller
{

    public $defaultDescription  = 'SpringСonsulting - ваша возможность понять себя, реализовать свой потенциал, мечты, желания, цели! Профессиональная поддержка опытного коуча-консультанта и сопровождение в поисках ответов на жизненно важные вопросы, в поиске работы, в построении гармоничных отношений,  в достижении счастья и успеха';
    public $defaultKeywords     = '';
    public $arrMenu             = array();

    public function __construct()
    {
       parent::__construct();
        $this->arrMenu           = $this->_prepareMenu();
        $this->urlArr            = explode('/',$_SERVER['REQUEST_URI']);
    }


    public function show_landing_page($code)
    {
        $landingPageData = $this->landing_model->getLandingPageByUnique($code);
      
        if(!count($landingPageData)) redirect('/index');        
        $this->data_arr       = array(
            'title'             => SITE_TITLE.' - landing page',
            'meta_keywords'	    => $this->defaultDescription,
            'meta_description'	=> $this->defaultKeywords,
            'content'           => $landingPageData,
            'titleFB'         	=> SITE_TITLE.' - '.(count($landingPageData) > 0 && $landingPageData['title']) ? $landingPageData['title'] : '',
            'imgFB'         	=> 'spring_logo.png'
        );

       $data = array(
             'content'       => $this->load->view('blocks/landing_page', $this->data_arr, true),
             'subscribe'     => $this->load->view('blocks/landing_subscribe', $this->data_arr, true));
       $this->load->view('layout_landing', $data);
    }


    public function show_landing_article($id)
    {
        $landingArticleData = $this->landing_model->getLandingArticleById($id);
      
        if(!$landingArticleData) redirect('/index');   
        $this->data_menu      = array('menu' => $this->arrMenu,'current_url' => $this->urlArr[count($this->urlArr)-1]);        
        $this->data_arr       = array(
            'title'             => SITE_TITLE.' - закрытая система мероприятий',
            'meta_keywords'	    => $this->defaultDescription,
            'meta_description'	=> $this->defaultKeywords,
            'content'           => $landingArticleData,
            'disqus'            => show_disqus()
        );

       $data = array(
             'menu'          => $this->load->view(MENU, $this->data_menu, true),           
             'content'       => $this->load->view('blocks/landing_article', $this->data_arr, true),
             'downloads'     => $this->load->view('blocks/landing_downloads', $this->data_arr, true));
       $this->load->view('layout_landing_articles', $data);
    }

    
    public function ajax_landing_subscribe()
    {
        $data = array();
        $data['name']  = trim(strip_tags($_REQUEST['name']));
        $data['email'] = trim(strip_tags($_REQUEST['email']));
        $data['created_at'] = date('Y-m-d H:i:s');

        return $this->check_valid_landing_form($data);
    }


    public function check_valid_landing_form($data)
    {
        try{
            $rules      = $this->_prepareRulesSubscribeForm();
            $this->_checkValid($rules);
            $data['confirmed'] = STATUS_ON;

            $arrRecipientData               = $this->index_model->getRecipientData($data);
            $landingData['landing_page_id'] = trim(strip_tags($_REQUEST['landing_page_id']));
            $landingData['recipients_id']   = $arrRecipientData['id'];
            $landingData['date_visited']    = date('Y-m-d H:i:s');
            $arrLandingStatisticsData       = $this->index_model->getFromTableByParams(array('landing_page_id' => $landingData['landing_page_id'], 'recipients_id' => $landingData['recipients_id']), 'landing_statistics');
            Common::assertFalse(count($arrLandingStatisticsData), "Вы уже зарегистрированы на данное мероприятие!");
            $landingStatisticsId        = $this->index_model->addInTable($landingData, 'landing_statistics');            
            Common::assertTrue($landingStatisticsId, "<p class='error'>К сожалению, регистрация прошла неудачно.<br/>Пожалуйста, попробуйте еще раз</p>");
            $landingPageData = $this->index_model->getFromTableByParams(array('id' => $landingData['landing_page_id']), 'landing_page');
            $data['text'] = '<p>Новая подписка на треннинг : "'.trim(strip_tags($_REQUEST['title'])).'"</p>';

            $this->mailer_model->sendLandingSubscribeEmailMessage($landingPageData[0], $arrRecipientData);
            $this->mailer_model->sendEmailMessage($data);
            
            $this->result['success'] = true;
            $this->result['data']    = "<p class='success'>Спасибо за регистрацию!<br/>На Ваш почтовый ящик была отправлена подробная инструкция<br/>(проверьте папку Входящие и СПАМ)</p>";
        } catch (Exception $e){
            $this->result['message'] = $e->getMessage();
        }

        print json_encode($this->result);
        exit;
    }
    

    public function ajax_get_landing_mp3()
    {
        $data = array();
        $data['email']              = trim(strip_tags($_REQUEST['email']));
        $data['landing_page_id']    = trim(strip_tags($_REQUEST['landing_page_id']));
        $data['landing_article_id'] = trim(strip_tags($_REQUEST['landing_article_id']));
      
        return $this->check_valid_download_form($data);
    }


    public function check_valid_download_form($data)
    {
        try{
            $rules = $this->_prepareRulesDownloadForm();
            $this->_checkValid($rules);

            $landingArticleData   = $this->landing_model->getLandingArticleData($data);
            Common::assertTrue(count($landingArticleData), "<p class='error'>К сожалению, введенный E-mail<br/> 
                                                            не регистрирован на данное мероприятие<br/> 
                                                            и не может получить доступ к скачиванию материала.</p>");            

            $this->result['data']       = "<p class='success'>Чтобы скачать материал по теме<br/>  
                                            <b>'".$landingArticleData['title']."'</b><br/>
                                            перейдите по ссылке:<br> 
                                            <a href='".$landingArticleData['link_mp3']."'>'".$landingArticleData['link_mp3']."'</a><br/>
                                            и воспользуйтесь паролем<br/> 
                                            <b>".$landingArticleData['password_mp3']."</b></p>";
            $this->result['success']    = true;            
        } catch (Exception $e){
            $this->result['message'] = $e->getMessage();
        }

        print json_encode($this->result);
        exit;
    }

    
    private function _prepareRulesDownloadForm()
    {
         return array(  'field'	=> 'email',
                        'label'	=> 'Email не заполнен',
                        'rules'	=> 'required|email');
    }


    protected function _prepareRulesSubscribeForm()
    {
        return array(
            array(
                'field'	=> 'name',
                'label'	=> 'Name',
                'rules'	=> 'required|xss_clean'),
            array(
                'field'	=> 'email',
                'label'	=> 'Email',
                'rules'	=> 'required|valid_email')
        );
    }


    protected function _checkValid($rules)
    {
        $isValid = $this->form_validation->set_rules($rules);
        Common::assertTrue($isValid, "<p class='error'>Форма заполнена неверно</p>");

        return true;
    }



    protected function _prepareMenu()
    {
        return $this->menu_model->childs;
    }


}