<?php

/**
 *
 * DISQUS
 *
 * @Litkovskiy 2011
 */

if (!defined('BASEPATH')) exit('No direct script access allowed');

    function show_disqus () {
        $CI = &get_instance ();
        $disqus = $CI->load->view ('helpers/disqus_block','',TRUE);

        return $disqus;
			
    }