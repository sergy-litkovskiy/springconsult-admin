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

    public function updateMenu()
    {
        return $this->output
            ->set_content_type($this->contentTypeJson)
            ->set_status_header(400, 'Some error occurred during processing');
//        return $this->saveMenu(
//            function ($articleData) {
//                $articleId = ArrayHelper::arrayGet($articleData, 'id');
//
//                if (!$this->blog_model->update($articleId, $articleData)) {
//                    throw new RuntimeException(sprintf('Article with ID#%s was not updated', $articleId));
//                }
//
//                return $articleId;
//            }
//        );
    }

    private function saveMenu(Closure $fn)
    {
        $errorCode = 409;

        try {
            if (!$data = json_decode(file_get_contents('php://input'), true)) {
                $errorCode = 400;
                throw new LogicException('Request data is empty');
            }

//            $this->tryToUploadFile($data);
//
//            $articleData = $this->makeMainArticleData($data);
//
//            $id = $fn($articleData);
//            $this->processAssignedMenuList($id, $data);
        } catch (Exception $e) {
            return $this->output
                ->set_content_type($this->contentTypeJson)
                ->set_status_header($errorCode, $e->getMessage());
        }

        return $this->output
            ->set_content_type($this->contentTypeJson)
            ->set_output(json_encode(['OK']));
    }
}
