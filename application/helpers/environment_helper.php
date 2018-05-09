<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

    function isTestingEnv()
    {
        return (ENVIRONMENT == 'testing');
    }

    function getCurrentDomain()
    {
        return (ENVIRONMENT == 'testing') ? base_url() : cdn_url();
    }

    function getDebugAngularMediaDomain()
    {
        return 'http://192.168.50.77:4200/';
    }
