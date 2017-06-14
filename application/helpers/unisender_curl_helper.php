<?php

/**
 *
 * UNISENDER CURL helper
 *
 * @Litkovskiy 2012
 */

if (!defined('BASEPATH')) exit('No direct script access allowed');

    function startCurlExec($postArr, $url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postArr);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_URL, $url);
        return curl_exec($ch);
    }
