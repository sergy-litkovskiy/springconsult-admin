<?php
/**
 * @author Litkovsky
 * @copyright 2010
 * model for index page
 */
 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Search_model extends Crud
{
    public function __construct()
    {
        parent::__construct();
    }


    public function getSearchContent($text)
    {
        $searchResultArr = array();
        
        $searchingText = mb_convert_case($text, MB_CASE_LOWER, "UTF-8");
        
        $searchResultArr['menuArr']         = $this->_getSearchMenuSql($searchingText);
        $searchResultArr['articlesArr']     = $this->_getSearchArticlesSql($searchingText);
        $searchResultArr['materialsArr']    = $this->_getSearchMaterialsSql($searchingText);

        return $searchResultArr;
    }

    
    private function _getSearchMenuSql($searchingText)
    {
        $sql  = $this->_prepareSearchMainSqlSelect('menu');
        $sql .= $this->_prepareSearchMainSqlWhere($searchingText);
        $queryMenu = $this->db->query($sql);
        
        return $queryMenu->result_array();
    }


    private function _getSearchArticlesSql($searchingText)
    {
        $sql  = $this->_prepareSearchMainSqlSelect('article');
        $sql .= $this->_prepareSearchMainSqlWhere($searchingText);
        $queryArticles = $this->db->query($sql);

        return $queryArticles->result_array();
    }


    private function _getSearchMaterialsSql($searchingText)
    {
        $sql  = $this->_prepareSearchMainSqlSelect('materials');
        $sql .= " AND (LOWER(rus_name) REGEXP '^".$searchingText."' OR LOWER(rus_name) REGEXP ' ".$searchingText."' OR LOWER(rus_name) REGEXP '>".$searchingText."')";
        $queryArticles = $this->db->query($sql);

        return $queryArticles->result_array();
    }


    private function _prepareSearchMainSqlSelect($table)
    {
        return "SELECT * FROM ".$table." WHERE status = ".STATUS_ON;
    }


    private function _prepareSearchMainSqlWhere($searchingText)
    {
        return " AND ((LOWER(title) REGEXP '^".$searchingText."' OR LOWER(text) REGEXP '^".$searchingText."') 
            OR (LOWER(title) REGEXP ' ".$searchingText."' OR LOWER(text) REGEXP ' ".$searchingText."')
            OR (LOWER(title) REGEXP '>".$searchingText."' OR LOWER(text) REGEXP '>".$searchingText."'))";
    }

}