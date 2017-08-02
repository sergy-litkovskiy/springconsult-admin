<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Menu extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getList()
    {
        $articleList = $this->menu_model->getList();

        return $this->output
            ->set_content_type($this->contentTypeJson)
            ->set_output(json_encode($articleList));
    }
}