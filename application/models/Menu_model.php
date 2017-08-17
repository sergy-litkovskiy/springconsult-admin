<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Menu_model extends Crud
{
    public function __construct()
    {
        parent::__construct();

        $this->table = 'menu';
    }

    public function getAssignedMenuList(array $articleIdList)
    {
        $this->db->select($this->table . '.*, maa.article_id as article_id');
        $this->db->join(
            'menu_article_assignment as maa',
            $this->table . '.id = maa.menu_id',
            'INNER'
        );

        $this->db->where_in('maa.article_id', $articleIdList);

        $query = $this->db->get($this->table);

        return $query->result_array();
    }

    public function deleteAssignmentByArticleId($articleId)
    {
        $this->table = 'menu_article_assignment';

        return $this->deleteByParams(['article_id' => $articleId]);
    }

    public function assignArticleToMenu($articleId, $menuId)
    {
        $this->table = 'menu_article_assignment';

        return $this->add(['article_id' => $articleId, 'menu_id' => $menuId]);
    }
}