<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Blog extends MY_Controller
{
    protected $entityName = 'blog';
    protected $defaultPageTitle = 'Блог';

    public function index($page = 1)
    {
        $metaData = $this->getMainData();

        $countTotal = $this->blog_model->getCountByParams(['status' => STATUS_ON]);

        $baseUrl = sprintf('%s%s', base_url(), $this->entityName);

        $pagerView = $this->preparePager($baseUrl, $countTotal, null);

        list($orderParams, $limitParams) = $this->makeSqlParams($page);

        $articleList = $this->blog_model->getListByParams(['status' => STATUS_ON], $orderParams, $limitParams);

        $topicList = $this->topic_model->getTopicListByParamsWithArticleCount(['status' => STATUS_ON]);

        $data = [
            'currentItemName' => $this->entityName,
            'articleList'     => $articleList,
            'metaData'        => $metaData,
            'topicList'       => $topicList,
            'pager'           => $pagerView,
            'pageTitle'       => $this->defaultPageTitle
        ];

        $data = array_merge($data, $this->baseResult);

        $this->twig->display($this->entityName . '/index.html', $data);
    }

    public function show($articleId)
    {
        $metaData = $this->getMainData();

        $articleList = $this->blog_model->getListByParams(['status' => STATUS_ON, 'id' => $articleId]);

        $topicList = $this->topic_model->getTopicListByParamsWithArticleCount(['status' => STATUS_ON]);

        $data = [
            'currentItemName' => $this->entityName,
            'data'            => ArrayHelper::arrayGet($articleList, 0, []),
            'metaData'        => $metaData,
            'topicList'       => $topicList,
            'pageTitle'       => ArrayHelper::arrayGet($articleList, '0.title'),
            'disqus'          => show_disqus(),
            'disqusId'        => sprintf('article_%s_identifier', $articleId)
        ];

        $data = array_merge($data, $this->baseResult);

        $this->twig->display($this->entityName . '/show.html', $data);
    }

    public function topic($topicId, $slug, $page = 1)
    {
        $topicTitle = '';
        $metaData = $this->getMainData();

        $countTotal = $this->blog_model->getCountArticlesForTopicByParams($topicId, ['status' => STATUS_ON]);

        $baseUrl = sprintf('%s%s/topic/%s', base_url(), $this->entityName, $topicId);

        $pagerView = $this->preparePager($baseUrl, $countTotal, $slug, 6);

        list($orderParams, $limitParams) = $this->makeSqlParams($page);

        $articleList = $this->blog_model->getArticlesForTopicByParams(
            $topicId,
            ['status' => STATUS_ON],
            $orderParams,
            $limitParams
        );

        $topicList = $this->topic_model->getTopicListByParamsWithArticleCount(['status' => STATUS_ON]);

        //remove current topic from the list and set current topic name
        $topicList = array_filter($topicList, function ($value) use ($topicId, &$topicTitle) {
            if (ArrayHelper::arrayGet($value, 'id') == $topicId) {
                $topicTitle = ArrayHelper::arrayGet($value, 'name');
                return false;
            }

            return true;
        });

        $data = [
            'currentItemName' => $this->entityName,
            'articleList'     => $articleList,
            'metaData'        => $metaData,
            'topicList'       => $topicList,
            'pager'           => $pagerView,
            'pageTitle'       => $this->defaultPageTitle,
            'topicTitle'      => $topicTitle
        ];

        $data = array_merge($data, $this->baseResult);

        $this->twig->display($this->entityName . '/index.html', $data);
    }

    private function getMainData()
    {
        $mainData = $this->menu_model->getListByParams(['status' => STATUS_ON, 'id' => MENU_TOP_LEVEL_ID_BLOG]);
        $mainData = ArrayHelper::arrayGet($mainData, 0, []);

        return $this->prepareMetaData($mainData);
    }

    private function preparePager($baseUrl, $countTotal, $slug, $uriSegment = null)
    {
        //prepare pager config
        $config               = prepare_pager_config();
        $config['base_url']   = sprintf('%s/%s/page/', $baseUrl, $slug);
        $config['first_url']  = $baseUrl;
        $config['total_rows'] = $countTotal;

        if ($uriSegment) {
            $config['uri_segment'] = $uriSegment;
        }

        if ($slug) {
            $config['first_url'] = $baseUrl.'/'.$slug;
        }

        $this->pagination->initialize($config);

        return $this->pagination->create_links();
    }

    private function makeSqlParams($page)
    {
        $limit  = $this->pagination->per_page;
        $offset = $limit * ($page - 1);

        $orderParams = [
            'orderBy'        => 'date',
            'orderDirection' => ORDER_DIRECTION_DESC
        ];

        $limitParams = [
            'limit'  => $limit,
            'offset' => $offset
        ];

        return [$orderParams, $limitParams];
    }

    /**
     * Generate path to image for FB using article inside article text
     * @param $data
     * @return string
     */
    protected function makeFbImage($data)
    {
        $imageName = ImageHelper::getFirstImgFromText(ArrayHelper::arrayGet($data, 'text'), null);

        if (is_null($imageName)) {
            //return default fbImage
            return parent::makeFbImage($data);
        }

        return ImageHelper::makeFbImage('img/blog/', $imageName);
    }

    protected function makeMetaType()
    {
        return 'article';
    }
}