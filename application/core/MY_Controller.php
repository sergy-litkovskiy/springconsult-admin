<?php

defined('BASEPATH') OR exit('No direct script access allowed');

abstract class MY_Controller extends CI_Controller
{
    /** @var  Index_model */
    public $index_model;
    /** @var  Blog_model */
    public $blog_model;
    /** @var  Sale_model */
    public $sale_model;
    /** @var  Menu_model */
    public $menu_model;
    /** @var  Review_model */
    public $review_model;
    /** @var  Topic_model */
    public $topic_model;
    /** @var  Mailer_model */
    public $mailer_model;
    /** @var  SaleCategory_model */
    public $saleCategory_model;
    /** @var  SaleHistory_model */
    public $saleHistory_model;
    /** @var  SalePage_model */
    public $salePage_model;
    /** @var  SaleProductImage_model */
    public $saleProductImage_model;
    /** @var  Recipient_model */
    public $recipient_model;
    /** @var  Linkspacker_model */
    public $linkspacker_model;
    /** @var  ErrorLog_model */
    public $errorLog_model;

    /** @var  CI_Pagination */
    public $pagination;
    /** @var  Tags_model */
    public $tags_model;
    /** @var  CI_Form_validation */
    public $form_validation;
    /** @var  Twig */
    public $twig;
    /** @var  CI_Input */
    public $input;
    /** @var  CI_Output */
    public $output;

    protected $lastArticleList     = [];
    protected $lastSaleProductList = [];
    protected $baseResult = [];

    protected $entityName;
    protected $contentTypeJson;

    public function __construct()
    {
        parent::__construct();

        $this->contentTypeJson = 'application/json';
    }
}