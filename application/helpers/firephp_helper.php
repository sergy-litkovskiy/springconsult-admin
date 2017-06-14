<?php

/**
 *
 * FIREPHP helper
 *
 * @Litkovskiy 2012
 */

if (!defined('BASEPATH')) exit('No direct script access allowed');

    function fb ($var) {
        $CI = &get_instance ();
        return $CI->firephp->fb($var);
    }
