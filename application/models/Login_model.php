<?php

/**
 * @author Litkovsky
 * @copyright 2010
 * model for object profile
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Login_model extends Crud{
	
    var $table = 'userlist';
    var $logkey = 'login';
    var $paskey = 'pass';
	
    function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Check login and password
     */
    public function checkLogPass($log, $pass)
    {
        $this->db->where($this->logkey, $log);
        $this->db->where($this->paskey, md5($pass));
        $query = $this->db->get($this->table);
        $numr  = $query->num_rows();
        if($numr !== 0){
            return $this->_logIn($log, $pass);
        }
        else{
            return false;
        }
   }
   
    /**
     * Start session width data array
     */
    private function _logIn($log, $pass)
    {
        $newdata = array(
                   'username' => $log.'_admin',
                   'loggedIn' => TRUE
               );

        //start session and redirect to admin page
        $this->session->set_userdata($newdata);
        return $this->session->userdata('username') ? true : false;
    }
    
    /**
     * Unset session
     */
    public function logOut()
    {
        $this->session->sess_destroy();
        redirect('backend');
    }
}