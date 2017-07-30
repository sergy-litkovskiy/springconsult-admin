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
        return $this->saveArticle(
            function ($articleData) {
                $articleId = ArrayHelper::arrayGet($articleData, 'id');

                if (!$this->blog_model->update($articleId, $articleData)) {
                    throw new RuntimeException(sprintf('Article with ID#%s was not updated', $articleId));
                }
            }
        );
    }

    public function addArticle()
    {
        return $this->saveArticle(
            function ($articleData) {
                if (!$this->blog_model->add($articleData)) {
                    throw new RuntimeException('New Article was not inserted');
                }
            }
        );
    }

    private function saveArticle(Closure $fn)
    {
        $errorCode = 409;

        try {
            if (!$data = json_decode(file_get_contents('php://input'), true)) {
                $errorCode = 400;
                throw new LogicException('Request data is empty');
            }

            $this->tryToUploadFile($data);

            $articleData = $this->makeMainArticleData($data);

            $fn($articleData);
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
            $articleId                       = ArrayHelper::arrayGet($articleData, 'id');
            $articleData['assignedMenuList'] = ArrayHelper::arrayGet($assignedMenuMap, $articleId, []);
        }

        return $articleList;
    }

    private function makeAssignedMenuMap(array $articleIdList)
    {
        $map              = [];
        $assignedMenuList = $this->menu_model->getAssignedMenuList($articleIdList);

        foreach ($assignedMenuList as $assignedMenu) {
            $articleId         = ArrayHelper::arrayGet($assignedMenu, 'article_id');
            $map[$articleId][] = $assignedMenu;
        }

        return $map;
    }

    private function makeMainArticleData(array $data)
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
}