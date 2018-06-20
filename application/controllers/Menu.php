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
        $menuList = $this->menu_model->getList();
        $menuMap = array_values($this->makeMenuMap($menuList));

        return $this->output
            ->set_content_type($this->contentTypeJson)
            ->set_output(json_encode($menuMap));
    }

    private function makeMenuMap(array $menuList)
    {
        $map = [];

        foreach ($menuList as $menuItem) {
            $parentId = ArrayHelper::arrayGet($menuItem, 'parent');
            $id = ArrayHelper::arrayGet($menuItem, 'id');

            if (!$parentId) {
                if (!ArrayHelper::arrayHas($map, $id)) {
                    $map[$id] = ['parent' => $menuItem];
                    continue;
                }

                $map[$id]['parent'] = $menuItem;
            } else {
                if (!ArrayHelper::arrayHas($map, $parentId)) {
                    $map[$parentId] = ['childList' => [$menuItem]];
                    continue;
                }

                $map[$parentId]['childList'][] = $menuItem;
            }
        }

        return $map;
    }
}
