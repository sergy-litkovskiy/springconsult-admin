<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Menu_model extends Crud
{
    public $id, $parent, $title, $slug, $meta_description, $meta_keywords;
    public  $childs = array();
    protected $table = 'menu';

    function __construct($id = 0)
    {
        parent::__construct();
//        $query_parent         = $this->db->query("SELECT
//                                                        menu.*
//                                                    FROM
//                                                        menu
//                                                    WHERE
//                                                        menu.id = ".$id."
//                                                    AND
//                                                        menu.status = 1
//                                                    ORDER by
//                                                        menu.num_sequence");
//        $arr_menu_parent_item = $query_parent->result_array();
//
//        $this->id      = empty($arr_menu_parent_item)              ? 0 : $arr_menu_parent_item[0]['id'];
//        $this->parent  = empty($arr_menu_parent_item[0]['parent']) ? 0 : $arr_menu_parent_item[0]['parent'];
//        $this->title   = empty($arr_menu_parent_item[0]['title'])  ? 0 : $arr_menu_parent_item[0]['title'];
//        $this->slug    = empty($arr_menu_parent_item[0]['slug'])   ? 0 : $arr_menu_parent_item[0]['slug'];
//
//        $this->meta_description = empty($arr_menu_parent_item[0]['meta_description'])   ? 0 : $arr_menu_parent_item[0]['meta_description'];
//        $this->meta_keywords    = empty($arr_menu_parent_item[0]['meta_keywords'])      ? 0 : $arr_menu_parent_item[0]['meta_keywords'];
//        $query_childs  = $this->db->query("SELECT
//                                                menu.*
//                                            FROM
//                                                menu
//                                            WHERE
//                                                menu.parent = ".$this->id."
//                                            AND
//                                                menu.status = 1
//                                            ORDER by
//                                                menu.num_sequence");
//        $arr_menu_item = $query_childs->result_array();
//
//        foreach($arr_menu_item as $val){
//            $child = new Menu_model($val['id']);
//            $this->childs[] = $child;
//        }
    }

    public function getMenuListByIdList(array $ids)
    {
        $idList = implode(',', $ids);
        $sql = sprintf(
            'SELECT menu.* FROM menu WHERE menu.id IN (%s) AND menu.status = %s ORDER by menu.num_sequence',
            $idList,
            STATUS_ON
        );

        $query = $this->db->query($sql);

        return $query->result_array();
    }

    public function getTopLevelMenuList()
    {
        $sql = sprintf(
            'SELECT menu.* FROM menu WHERE menu.parent = 0 AND menu.status = %s ORDER by menu.num_sequence',
            STATUS_ON
        );

        $query = $this->db->query($sql);

        return $query->result_array();
    }

    public function getMenuListByParentId($id)
    {
        $sql = sprintf(
            'SELECT menu.* FROM menu WHERE menu.parent = %s AND menu.status = %s ORDER by menu.num_sequence',
            $id,
            STATUS_ON
        );

        $query = $this->db->query($sql);

        return $query->result_array();
    }

    public function getMenuListWithReviewsByParentId($id)
    {
        $sql = sprintf(
            '
            SELECT 
                menu.*,
                review.id as review_id, 
                review.author as review_author, 
                review.text as review_text, 
                review.image as review_image
            FROM 
                menu 
            LEFT JOIN 
                menu_review_assignment as mra
              ON 
                mra.menu_id = menu.id 
            LEFT JOIN 
                review
              ON 
                mra.review_id = review.id AND review.status = %s              
            WHERE 
                menu.parent = %s 
            AND 
                menu.status = %s 
            ORDER by 
                menu.num_sequence
            ',
            STATUS_ON,
            $id,
            STATUS_ON
        );

        $query = $this->db->query($sql);

        return $query->result_array();
    }

    public function getReviewListByMenuId($id)
    {
        $sql = sprintf(
            '
            SELECT 
                review.*
            FROM 
                review 
            INNER JOIN 
                menu_review_assignment as mra
            ON 
                mra.review_id = review.id 
            AND 
                review.status = %s    
            AND 
                mra.menu_id = %s
            ',
            STATUS_ON,
            $id
        );

        $query = $this->db->query($sql);

        return $query->result_array();
    }

    public function getArticleListByMenuId($id, $limit = null)
    {
        $sql = sprintf(
            '
            SELECT 
                article.id,
                article.title,
                article.image,
                article.date,
                article.slug
            FROM 
                article 
            INNER JOIN 
                menu_article_assignment as aa
            ON 
                aa.article_id = article.id 
            AND 
                article.status = %s    
            AND 
                aa.menu_id = %s
            ORDER BY 
                article.date DESC  
            %s 
            ',
            STATUS_ON,
            $id,
            $limit ? ' LIMIT '.$limit : null
        );

        $query = $this->db->query($sql);

        return $query->result_array();
    }
}