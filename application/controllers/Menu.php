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

        $menuList = $this->extendWithAssignedSaleProducts($menuList);

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
        return $this->saveMenu(
            function ($menuItem) {
                $menuId = ArrayHelper::arrayGet($menuItem, 'id');

                if (!$this->menu_model->update($menuId, $menuItem)) {
                    throw new RuntimeException(sprintf('Menu item with ID#%s was not updated', $menuId));
                }

                return $menuId;
            }
        );
    }

    private function saveMenu(Closure $fn)
    {
        $errorCode = 409;

        try {
            if (!$data = json_decode(file_get_contents('php://input'), true)) {
                $errorCode = 400;
                throw new LogicException('Request data is empty');
            }

            $this->tryToUploadFile($data);

            $menuData = $this->makeMainMenuData($data);

            $id = $fn($menuData);
            $this->processAssignedSaleProductsList($id, $data);
        } catch (Exception $e) {
            return $this->output
                ->set_content_type($this->contentTypeJson)
                ->set_status_header($errorCode, $e->getMessage());
        }

        return $this->output
            ->set_content_type($this->contentTypeJson)
            ->set_output(json_encode(['OK']));
    }

    //TODO: implement for Menu
    private function makeMainMenuData(array $data)
    {
        return [
            'id'               => ArrayHelper::arrayGet($data, 'id'),
            'image'            => ArrayHelper::arrayGet($data, 'image'),
            'description'      => ArrayHelper::arrayGet($data, 'description'),
            'title'            => ArrayHelper::arrayGet($data, 'title'),
            'slug'             => ArrayHelper::arrayGet($data, 'slug'),
            'text'             => ArrayHelper::arrayGet($data, 'text'),
            'meta_keywords'    => ArrayHelper::arrayGet($data, 'metaKeywords'),
            'meta_description' => ArrayHelper::arrayGet($data, 'metaDescription'),
            'num_sequence'     => ArrayHelper::arrayGet($data, 'numSequence'),
            'status'           => ArrayHelper::arrayGet($data, 'status'),
            'is_sent_mail'     => ArrayHelper::arrayGet($data, 'isSentMail'),
        ];
    }

    //TODO: implement for Menu
    private function tryToUploadFile(array $data)
    {
        if (!$imageInBase64 = ArrayHelper::arrayGet($data, 'imageData')) {
            return;
        }

        //upload file to server
        if (!$result = FileLoader::uploadFileInBase64(
            ArrayHelper::arrayGet($data, 'image'),
            IMAGE_UPLOAD_PATH_BLOG,
            $imageInBase64
        )) {
            throw new RuntimeException('Image for article was not uploaded');
        }
    }

    //TODO: implement for Menu
    private function processAssignedSaleProductsList($articleId, array $data)
    {
        if (!$assignedMenuList = ArrayHelper::arrayGet($data, 'assignedMenuList')) {
            return;
        }

        $this->menu_model->deleteAssignmentByArticleId($articleId);

        foreach ($assignedMenuList as $assignedMenu) {
            $this->menu_model->assignArticleToMenu($articleId, ArrayHelper::arrayGet($assignedMenu, 'id'));
        }
    }

    //TODO: implement for Menu
    private function extendWithAssignedSaleProducts(array $menuList)
    {
        $menuIdList = array_map(function ($menuItem) {
            return ArrayHelper::arrayGet($menuItem, 'id');
        }, $menuList);

        $assignedSaleProductsMap = $this->makeAssignedSaleProductsMap($menuIdList);

        foreach ($menuList as &$menuData) {
            $menuId = ArrayHelper::arrayGet($menuData, 'id');
            $menuData['assignedSaleProductList'] = ArrayHelper::arrayGet($assignedSaleProductsMap, $menuId, []);
        }

        return $menuList;
    }

    //TODO: implement for Menu
    private function makeAssignedSaleProductsMap(array $menuIdList)
    {
        $map              = [];
        $assignedSaleProductList = $this->menu_model->getAssignedMenuList($menuIdList);

        foreach ($assignedSaleProductList as $assignedSaleProduct) {
            $menuId = ArrayHelper::arrayGet($assignedSaleProduct, 'menu_id');
            $map[$menuId][] = $assignedSaleProduct;
        }

        return $map;
    }
}
