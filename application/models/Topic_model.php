<?php
 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Topic_model extends Crud
{

    public function __construct()
    {
        parent::__construct();

        $this->idkey = 'id';
        $this->table = 'topic';
    }

    public function getTopicListWithArticles()
    {
        $selectSql =
            $this->table . '.*,
            article.id as article_id,
            article.title as article_title,
            article.status as article_status
        ';

        $this->db->select($selectSql);
        $this->db->join(
            'topic_article_assignment as taa',
            $this->table . '.id = taa.topic_id',
            'LEFT'
        );

        $this->db->join(
            'article',
            'article.id = taa.article_id AND article.status = ' . STATUS_ON,
            'LEFT'
        );

        $this->db->where([$this->table.'.status' => STATUS_ON]);

        $query = $this->db->get($this->table);

        return $query->result_array();
    }

    public function getTopicListWithArticlesAdmin()
    {
        $sql = $this->_getSelectSql();
        $sql .= " LEFT JOIN article ON article.id = topic_article_assignment.article_id";
        $query = $this->db->query($sql);

        return $query->result_array();
    }

//    public function getAssignedArticleListByTopicId($id)
//    {
//        $sql = $this->_getSelectSql();
//        $sql .= " LEFT JOIN article ON article.id = topic_article_assignment.article_id";
//        $sql .= " AND topic.id = ".$id;
//
//        $query = $this->db->query($sql);
//
//        return $query->result_array();
//    }

    public function getTopicListByParamsWithArticleCount($params)
    {
        $selectSql = '
            topic.*, 
            (
                SELECT count(0) 
                FROM topic_article_assignment as taa 
                INNER JOIN article ON article.id = taa.article_id AND article.status = '.STATUS_ON.' 
                WHERE taa.topic_id = topic.id
            ) as articles_count
        ';

        $this->db->select($selectSql);
        $this->db->where($params);
        $this->db->join(
            'topic_article_assignment as taa',
            $this->table . '.id = taa.topic_id',
            'LEFT'
        );
        $this->db->order_by('topic.name');
        $this->db->group_by('topic.id');

        $query = $this->db->get($this->table);

        return $query->result_array();
    }

    private function _getSelectSql()
    {
        return "SELECT
                    topic.*,
                    article.id as article_id,
                    article.title as article_title,
                    article.status as article_status
                FROM
                    topic
                LEFT JOIN
                    topic_article_assignment ON topic_article_assignment.topic_id = topic.id"
            ;
    }
}