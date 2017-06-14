<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/

$route['default_controller'] = 'service';
$route['404_override']       = 'errorController/index';

$route['message/send']        = 'index/ajax_message';
$route['(:any)/message/send'] = 'index/ajax_message';

////////////////////NEW ADMIN////////////////////////////
$route['adm'] = "app-angular/index";

////////////////////ADMIN////////////////////////////

$route['backend']        = "app-angular/login";
$route['backend/login']  = "app-angular/index_admin";
$route['backend/logout'] = "app-angular/index_admin/logout";

$route['ajax_change_status'] = "app-angular/menu_admin/ajax_change_status";
//$route['backend/ajax_change_status']              = "app-angular/index_admin/ajax_change_status";

$route['backend/article_edit/(:num)'] = 'app-angular/article_admin/article_edit/$1';
$route['backend/article_edit']        = 'app-angular/article_admin/article_edit';
$route['backend/check_valid_article'] = 'app-angular/article_admin/check_valid_article';
$route['backend/article_drop/(:num)'] = 'app-angular/article_admin/drop/$1';

$route['backend/topic_edit']        = 'app-angular/topic_admin/edit';
$route['backend/topic_edit/(:num)'] = 'app-angular/topic_admin/edit/$1';
$route['backend/topic_drop/(:num)'] = 'app-angular/topic_admin/drop/$1';
$route['backend/topic_save']        = 'app-angular/topic_admin/save';

$route['backend/sale_category_edit']        = 'app-angular/saleCategory_admin/edit';
$route['backend/sale_category_edit/(:num)'] = 'app-angular/saleCategory_admin/edit/$1';
$route['backend/sale_category_drop/(:num)'] = 'app-angular/saleCategory_admin/drop/$1';
$route['backend/sale_category_save']        = 'app-angular/saleCategory_admin/save';

$route['backend/sale_category_number_edit/(:num)'] = 'app-angular/saleCategory_admin/editNumber/$1';

$route['backend/review_edit']        = 'app-angular/review_admin/edit';
$route['backend/review_edit/(:num)'] = 'app-angular/review_admin/edit/$1';
$route['backend/review_drop/(:num)'] = 'app-angular/review_admin/drop/$1';
$route['backend/review_save']        = 'app-angular/review_admin/save';

$route['backend/check_valid_submenu']            = 'app-angular/menu_admin/check_valid_menu';
$route['backend/check_valid_menu']               = 'app-angular/menu_admin/check_valid_menu';
$route['backend/menu_admin']                     = 'app-angular/menu_admin/index';
$route['backend/menu_admin/item_edit']           = 'app-angular/menu_admin/edit_menu_item';
$route['backend/menu_admin/del/(:num)/(:num)']   = 'app-angular/menu_admin/drop/$1/$2';
$route['backend/menu_admin/item_edit/(:num)']    = 'app-angular/menu_admin/edit_menu_item/$1';
$route['backend/menu_admin/subitem_edit']        = 'app-angular/menu_admin/edit_menu_subitem';
$route['backend/menu_admin/subitem_edit/(:num)'] = 'app-angular/menu_admin/edit_menu_subitem/$1';

$route['backend/news']          = 'app-angular/index_admin/index';
$route['backend/topic']         = 'app-angular/topic_admin/index';
$route['backend/sale_category'] = 'app-angular/saleCategory_admin/index';
$route['backend/review']        = 'app-angular/review_admin/index';

//$route['backend/send_nl_subscribe/(:num)']        = 'app-angular/article_admin/ajax_send_article_to_subscribers/$1';

$route['backend/gift']             = 'app-angular/index_admin/gift_list';
$route['backend/check_valid_gift'] = 'app-angular/index_admin/check_valid_gift';
$route['backend/gift_edit']        = 'app-angular/index_admin/gift_edit';
$route['backend/gift_edit/(:num)'] = 'app-angular/index_admin/gift_edit/$1';
$route['backend/gift_drop/(:num)'] = 'app-angular/index_admin/gift_drop/$1';

$route['backend/material']             = 'app-angular/material_admin/material_list';
$route['backend/material_drop/(:num)'] = 'app-angular/material_admin/material_drop/$1';
$route['backend/material_edit']        = 'app-angular/material_admin/material_edit';
$route['backend/material_edit/(:num)'] = 'app-angular/material_admin/material_edit/$1';
$route['backend/check_valid_material'] = 'app-angular/material_admin/check_valid_materials';

//$route['backend/aforizmus']             = 'app-angular/index_admin/aforizmus_list';
//$route['backend/aforizmus_edit']        = 'app-angular/index_admin/aforizmus_edit';
//$route['backend/aforizmus_edit/(:num)'] = 'app-angular/index_admin/aforizmus_edit/$1';
//$route['backend/aforizmus_drop/(:num)'] = 'app-angular/index_admin/aforizmus_drop/$1';

$route['backend/landing']             = 'app-angular/landing_admin/landing_list';
$route['backend/landing_edit']        = 'app-angular/landing_admin/landing_edit';
$route['backend/landing_edit/(:num)'] = 'app-angular/landing_admin/landing_edit/$1';
$route['backend/landing_drop/(:num)'] = 'app-angular/landing_admin/landing_drop/$1';
$route['backend/check_valid_landing'] = 'app-angular/landing_admin/check_valid_landing';

$route['backend/landing_articles']             = 'app-angular/landing_admin/landing_articles_list';
$route['backend/landing_articles_edit']        = 'app-angular/landing_admin/landing_articles_edit';
$route['backend/landing_articles_edit/(:num)'] = 'app-angular/landing_admin/landing_articles_edit/$1';
$route['backend/landing_articles_drop/(:num)'] = 'app-angular/landing_admin/landing_articles_drop/$1';
$route['backend/check_valid_landing_articles'] = 'app-angular/landing_admin/check_valid_landing_articles';

$route['backend/sale_page_list']        = 'app-angular/sale_admin/sale_page_list';
$route['backend/sale_page_edit']        = 'app-angular/sale_admin/sale_page_edit';
$route['backend/sale_page_edit/(:num)'] = 'app-angular/sale_admin/sale_page_edit/$1';
$route['backend/sale_page_drop/(:num)'] = 'app-angular/sale_admin/sale_page_drop/$1';
$route['backend/sale_page_save']        = 'app-angular/sale_admin/sale_page_save';

$route['backend/sale_product_list']        = 'app-angular/sale_admin/sale_product_list';
$route['backend/sale_product_edit']        = 'app-angular/sale_admin/sale_product_edit';
$route['backend/sale_product_edit/(:num)'] = 'app-angular/sale_admin/sale_product_edit/$1';
$route['backend/sale_product_drop/(:num)'] = 'app-angular/sale_admin/sale_product_drop/$1';
$route['backend/sale_product_save']        = 'app-angular/sale_admin/sale_product_save';
$route['backend/sale_product_statistic']   = 'app-angular/sale_admin/sale_product_statistic';
$route['backend/sale_product_number_edit/(:num)'] = 'app-angular/sale_admin/editNumber/$1';

$route['backend/announce_list']        = 'app-angular/announce_admin/announce_list';
$route['backend/check_valid_announce'] = 'app-angular/announce_admin/check_valid_announce';
$route['backend/announce_edit']        = 'app-angular/announce_admin/announce_edit';
$route['backend/announce_edit/(:num)'] = 'app-angular/announce_admin/announce_edit/$1';
$route['backend/announce_drop/(:num)'] = 'app-angular/announce_admin/announce_drop/$1';

$route['backend/spec_mailer_statistics']        = 'app-angular/index_admin/spec_mailer_statistics';
$route['backend/spec_mailer_statistics/(:num)'] = 'app-angular/index_admin/spec_mailer_statistics/$1';
$route['backend/(:any)']                        = 'app-angular/index_admin/show/$1';

////////////////////FRONTEND////////////////////////////
$route['service/(:num)/(:any)'] = 'service/show/$1';

$route['about'] = 'about/index';

$route['blog'] = 'blog/index';
$route['blog/page/(:num)'] = 'blog/index/$1';
$route['blog/topic/(:num)/(:any)'] = 'blog/topic/$1/$2';
$route['blog/topic/(:num)/(:any)/page/(:num)'] = 'blog/topic/$1/$2/$3';
$route['article/(:num)'] = 'blog/show/$1';

$route['review'] = 'review/index';

$route['shop'] = 'shop/index';
$route['shop/payment'] = 'shop/productPayment';
$route['shop/sale/(:num)/(:any)'] = 'shop/show/$1';

$route['gift/list'] = 'gift/ajaxGetGiftList';
$route['gift/subscribe'] = 'gift/ajaxGiftSubscribe';
$route['gift/finishsubscribe/(:num)/(:num)'] = 'gift/outputSubscribe/$1/$2';
$route['finishsubscribe/(:any)'] = 'gift/finishSubscribe/$1';
