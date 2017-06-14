<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Review extends MY_Controller
{
    protected $entityName = 'review';

    public function index()
    {
        $metaData = $this->getMainData();

        $topicList   = $this->topic_model->getTopicListByParamsWithArticleCount(['status' => STATUS_ON]);
        $serviceList = $this->menu_model->getMenuListByParentId(MENU_TOP_LEVEL_ID_SERVICE);

        $data = [
            'currentItemName' => $this->entityName,
            'disqus'          => show_disqus(),
            'disqusId'          => sprintf('article_%s_identifier', MENU_TOP_LEVEL_ID_REVIEW),
            'metaData'        => $metaData,
            'topicList'       => $topicList,
            'serviceList'     => $serviceList,
            'pageTitle'       => 'Отзывы клиентов'
        ];

        $data = array_merge($data, $this->baseResult);

        $this->twig->display($this->entityName . '/index.html', $data);
    }

    private function getMainData()
    {
        $mainData = $this->menu_model->getListByParams(['status' => STATUS_ON, 'id' => MENU_TOP_LEVEL_ID_REVIEW]);
        $mainData = ArrayHelper::arrayGet($mainData, 0, []);

        return $this->prepareMetaData($mainData);
    }
}