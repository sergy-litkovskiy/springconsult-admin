<?php

/**
 *
 * TinyMCE 
 *
 * @Litkovskiy 2010
 */

if (!defined('BASEPATH')) exit('No direct script access allowed');

		function show_tinymce () {
		
		$CI = &get_instance ();

		$code = $CI->load->view ('app-angular/helpers/tinymce','',TRUE);
		
		return $code;
			
	}
	

	function nocache_headers () {
		// Date in the past
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		
		// always modified
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		 
		// HTTP/1.1
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		
		// HTTP/1.0
		header("Pragma: no-cache");	
				
	}

?>