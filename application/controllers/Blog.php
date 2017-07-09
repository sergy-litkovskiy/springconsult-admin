<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Blog extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getList()
    {
        $articleList = $this->blog_model->getList();

        $articleList = $this->extendWithAssignedMenu($articleList);

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($articleList));
    }

    private function extendWithAssignedMenu(array $articleList)
    {
        $articleIdList = array_map(function ($articleItem) {
            return ArrayHelper::arrayGet($articleItem, 'id');
        }, $articleList);

        $assignedMenuMap = $this->makeAssignedMenuMap($articleIdList);

        foreach ($articleList as &$articleData) {
            $articleId = ArrayHelper::arrayGet($articleData, 'id');
            $articleData['assignedMenuList'] = ArrayHelper::arrayGet($assignedMenuMap, $articleId, []);
        }

        return $articleList;
    }

    private function makeAssignedMenuMap(array $articleIdList)
    {
        $map = [];
        $assignedMenuList = $this->menu_model->getAssignedMenuList($articleIdList);

        foreach ($assignedMenuList as $assignedMenu) {
            $articleId = ArrayHelper::arrayGet($assignedMenu, 'article_id');
            $map[$articleId][] = $assignedMenu;
        }

        return $map;
    }
}