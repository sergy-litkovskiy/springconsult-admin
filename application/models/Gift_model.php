<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Gift_model extends Crud
{
    public function __construct()
    {
        parent::__construct();

        $this->idkey = 'id';
        $this->table = 'gift';
    }

    public function getGiftListWithArticles()
    {
        $sqlSelect = '
            ' . $this->table . '.*,
            article.id as article_id,
            article.title as article_title,
            article.status as article_status
        ';

        $this->db->select($sqlSelect);
        $this->db->join(
            'gift_article_assignment as gaa',
            $this->table . '.id = gaa.gift_id',
            'LEFT'
        );
        $this->db->join(
            'article',
            'article.id = gaa.article_id AND article.status = ' . STATUS_ON,
            'LEFT'
        );

        $this->db->where($this->table . '.status', STATUS_ON);
        $this->db->group_by($this->table . '.id');

        $query = $this->db->get($this->table);

        return $query->result_array();
    }

    public function getGiftListWithArticlesAdmin()
    {
        $sqlSelect = '
            ' . $this->table . '.*,
            article.id as article_id,
            article.title as article_title,
            article.status as article_status
        ';

        $this->db->select($sqlSelect);
        $this->db->join(
            'gift_article_assignment as gaa',
            $this->table . '.id = gaa.gift_id',
            'LEFT'
        );

        $this->db->join(
            'article',
            'article.id = gaa.article_id',
            'LEFT'
        );

        $query = $this->db->get($this->table);

        return $query->result_array();
    }
}