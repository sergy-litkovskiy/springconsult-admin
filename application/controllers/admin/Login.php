<?php
/**
 * @author Litkovskiy
 * @copyright 2010
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller
{

    public $data_arr   = array();
    private $result;

    public function __construct()
    {
        parent::__construct();
        $this->result = array("success" => null, "message" => null, "data" => null);
    }

    public function index()
    {
       $this->data_arr  = array('title' => 'Springconsult - app-angular');
       $data = array('content' => $this->load->view('app-angular/login/show', $this->data_arr, true));

       $this->load->view('layout_login', $data);
    }


    public function ajax_login()
    {
        $data['login']    = isset($_REQUEST['log'])  ? trim(strip_tags($_REQUEST['log']))  : '';
        $data['pass']     = isset($_REQUEST['pass']) ? trim(strip_tags($_REQUEST['pass'])) : '';

        try{
            Common::assertTrue($data['login'], "<p class='error'>Введите логин</p>");
            Common::assertTrue($data['pass'], "<p class='error'>Введите пароль</p>");
            $rules = $this->_prepareRulesLoginForm();
            $this->_checkValid($rules);
            $result = $this->login_model->checkLogPass($data['login'], $data['pass']);
            Common::assertTrue($result, "<p class='error'>Вы ввели неверный логин или пароль</p>");

            $this->result['success'] = true;
        } catch (Exception $e){
            $this->result['message'] = $e->getMessage();
        }

        print json_encode($this->result);
        exit;

    }


    private function _prepareRulesLoginForm()
    {
        return array(
            array(
                'field'	=> 'log',
                'label'	=> 'login',
                'rules'	=> 'required'),
            array(
                'field'	=> 'pass',
                'label'	=> 'password',
                'rules'	=> 'required')
        );
    }


	private function _checkValid($rules)
	{
        $isValid = $this->form_validation->set_rules($rules);
        Common::assertTrue($isValid, "<p class='error'>Форма заполнена неверно</p>");

        return true;
    }

}