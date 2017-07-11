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
        $articleList = $this->blog_model->getListByParams([], ['orderDirection' => 'DESC']);

        $articleList = $this->extendWithAssignedMenu($articleList);

        return $this->output
            ->set_content_type($this->contentTypeJson)
            ->set_output(json_encode($articleList));
    }

    public function updateArticle()
    {
        $errorCode = 400;

        try {
            if (!$data = json_decode(file_get_contents('php://input'), true)) {
                throw new LogicException('Request data is empty');
            }

            $articleData = $this->makeMainArticleData($data);
            $articleId = ArrayHelper::arrayGet($data, 'id');

            if (!$this->blog_model->update($articleId, $articleData)) {
                $errorCode = 409;
                throw new RuntimeException(sprintf('Article with ID#%s was not updated', $articleId));
            }
        } catch (Exception $e) {
            return $this->output
                ->set_content_type($this->contentTypeJson)
                ->set_status_header($errorCode, $e->getMessage());
        }

        return $this->output
            ->set_content_type($this->contentTypeJson)
            ->set_output(json_encode(['OK']));
    }

    public function deleteArticle($id)
    {
        $errorCode = 400;

        try {
            if (!$id) {
                throw new LogicException('Article ID was not set');
            }

            if (!$this->blog_model->del($id)) {
                $errorCode = 409;
                throw new RuntimeException(sprintf('Article was not deleted'));
            }
        } catch (Exception $e) {
            return $this->output
                ->set_content_type($this->contentTypeJson)
                ->set_status_header($errorCode, $e->getMessage());
        }

        return $this->output
            ->set_content_type($this->contentTypeJson)
            ->set_output(json_encode(['OK']));
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

    private function makeMainArticleData(array $data)
    {
        return [
            'image' => ArrayHelper::arrayGet($data, 'image'),
            'description' => ArrayHelper::arrayGet($data, 'description'),
            'title' => ArrayHelper::arrayGet($data, 'title'),
            'slug' => ArrayHelper::arrayGet($data, 'slug'),
            'text' => ArrayHelper::arrayGet($data, 'text'),
            'meta_keywords' => ArrayHelper::arrayGet($data, 'meta_keywords'),
            'meta_description' => ArrayHelper::arrayGet($data, 'meta_description'),
            'num_sequence' => ArrayHelper::arrayGet($data, 'num_sequence'),
            'status' => ArrayHelper::arrayGet($data, 'status'),
            'is_sent_mail' => ArrayHelper::arrayGet($data, 'is_sent_mail'),
        ];
    }
}