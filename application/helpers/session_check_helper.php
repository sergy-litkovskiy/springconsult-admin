<?php

/**
 * @author Litkovskiy
 * @copyright 2010
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function session_check()
	{
		$CI =& get_instance();
		
		$code = $CI->load->view ('helpers/session_check','',TRUE);
		
		return $code; 
		
		
	}