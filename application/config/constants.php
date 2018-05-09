<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESCTRUCTIVE') OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three menu conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

/*
|--------------------------------------------------------------------------
| Constans for action message
|--------------------------------------------------------------------------
|
*/
define('MESS_SUCCESS', "<h2 class='mess_good'>The action is executed successfully!</h2>");
define('MESS_ERROR', "<h2 class='mess_bad'>The action was not executed. Please, try again.</h2>");

/*
|--------------------------------------------------------------------------
| Path for load menu
|--------------------------------------------------------------------------
|
*/
define('MENU', 'blocks/menu');
define('MENU_ADMIN', 'app-angular/blocks/menu_admin');

define('MENU_TOP_LEVEL_ID_SERVICE', 4);
define('MENU_TOP_LEVEL_ID_ABOUT', 1);
define('MENU_TOP_LEVEL_ID_REVIEW', 239);
define('MENU_TOP_LEVEL_ID_BLOG', 9);
define('MENU_TOP_LEVEL_ID_SHOP', 261);
define('MENU_TOP_LEVEL_ID_ABOUT_EDUCATION', 258);

define('ASSIGNED_ARTICLE_LIST_LIMIT', 3);

/*
|--------------------------------------------------------------------------
| Index for languages
|--------------------------------------------------------------------------
|
*/
define('LANGUAGE_ID_RU', '1');

/////////////////////////////////////////////////////////////

define('STATUS_ON', '1');
define('STATUS_OFF', '0');

define('ORDER_DIRECTION_ASC', 'ASC');
define('ORDER_DIRECTION_DESC', 'DESC');
define('ORDER_BY_DEFAULT', 'id');

define('ERROR_SRC_SPEC_MAILER', '1');
define('ERROR_SRC_ARTICLE_MAILER', '2');
define('ERROR_PAYMENT_REGISTRATION', '3');
define('ERROR_PAYMENT_CALLBACK', '4');

define('ALLOWED_UPLOAD_SIZE', 2111111);
define('ALLOWED_FORMAT', 'txt,doc,ppt,pps,pdf,docx,pptx,ppsx,pdfx,xls,xlsx,jpeg,jpg,gif,png,flw,swt');

define('ADMIN_EMAIL', 'spring@springconsult.com.ua');
//define('SUPERADMIN_EMAIL', 'avdik77@mail.ru');
define('SUPERADMIN_EMAIL', 'spring@springconsult.com.ua');
define('SITE_TITLE', 'Spring Consulting');

define('SALESTATUSID', '813A6CE48D37');
define('SALESHOPID', '531eca23bf4efc7c57cc6988');

define('PRIVAT_MERCHANT_ID', '122459');
define('PRIVAT_MERCHANT_PASS', '46h5u2Kvqz454049il6M7u7fZ5fPa1fi');
define('PRIVAT_PAYMENT_HTTP_REQUEST_URI', 'https://api.privatbank.ua/p24api/ishop');
define('PRIVAT_PAYMENT_XML_REQUEST_URI', 'https://api.privatbank.ua/p24api/pay_pb');
define('PRIVAT_XML_CHECK_PAY_URI', 'https://api.privatbank.ua/p24api/check_pay');
define('PRIVAT_PAYMENT_CURRENCY', 'UAH');

define('LIQPAY_PUBLIC_ID', 'i5887278126');
define('LIQPAY_PRIVAT_ID', 'PMODzlRD5zLvvIDHoNuojcYiTYc');
define('LIQPAY_HTTP_REQUEST_URI', 'https://www.liqpay.com/api/3/checkout');

define('UNISENDERAPIKEY', '581woumrc4iedxpdtsahhsy8hxkfew5q8xpp8tyy');
define('UNISENDERMAINLISTID', 1237963);
define('UNISENDERTESTLISTID', 1238223);

define('DEFAULT_META_DESCRIPTION', 'SpringСonsulting - ваша возможность понять себя, реализовать свой потенциал, мечты, желания, цели! Профессиональная поддержка опытного коуча-консультанта и сопровождение в поисках ответов на жизненно важные вопросы, в поиске работы, в построении гармоничных отношений,  в достижении счастья и успеха');
define('DEFAULT_META_KEYWORDS', '');
define('DEFAULT_FB_IMAGE', 'spring_logo_fb.png');

define('IMAGE_UPLOAD_PATH_BLOG', '/img/blog');

define('DEBUG_ANGULAR_MEDIA_HOST', 'http://192.168.50.77:4200/');//vagrant vm host