<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ErrorController extends MY_Controller
{
    public function index()
    {
        $data = [
            'pageTitle' => '404 Страница не найдена'
        ];

        $data = array_merge($data, $this->baseResult);

        $this->twig->display('error/index.html', $data);
    }
}