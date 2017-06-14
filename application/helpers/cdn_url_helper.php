<?php


if (!defined('BASEPATH')) exit('No direct script access allowed');

    function cdn_url()
    {
        $currentInstance =& get_instance();

        return $currentInstance->config->item('keycdn_url');
    }
