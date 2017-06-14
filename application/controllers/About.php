<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class About extends MY_Controller
{
    protected $entityName = 'about';

    public function index()
    {
        $mainData = $this->menu_model->get(MENU_TOP_LEVEL_ID_ABOUT);
        $mainData = ArrayHelper::arrayGet($mainData, 0, []);

        $educationData = $this->menu_model->get(MENU_TOP_LEVEL_ID_ABOUT_EDUCATION);
        $educationData = ArrayHelper::arrayGet($educationData, 0, []);

        $metaData = $this->prepareMetaData($mainData);

        $assignedArticleList = $this->menu_model->getArticleListByMenuId(MENU_TOP_LEVEL_ID_ABOUT);
        $reviewList          = $this->review_model->getListByParams(['status' => STATUS_ON]);

        $data = [
            'currentItemName'     => $this->entityName,
            'data'                => $mainData,
            'educationData'       => $educationData,
            'metaData'            => $metaData,
            'assignedArticleList' => $assignedArticleList,
            'reviewList'          => $reviewList,
            'pageTitle'           => 'Об авторе проекта'
        ];

        $data = array_merge($data, $this->baseResult);

        $this->twig->display($this->entityName . '/index.html', $data);
    }
}