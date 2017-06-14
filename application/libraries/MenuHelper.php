<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class MenuHelper
{
    public static function getTopLevelMenuIdList()
    {
        return [
          MENU_TOP_LEVEL_ID_SERVICE,
          MENU_TOP_LEVEL_ID_ABOUT,
          MENU_TOP_LEVEL_ID_SHOP,
          MENU_TOP_LEVEL_ID_BLOG,
          MENU_TOP_LEVEL_ID_CONTACTS,
        ];
    }
}