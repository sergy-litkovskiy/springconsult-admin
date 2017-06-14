<?php
/**
 * @author Litkovsky
 * @copyright 2010
 * model for index page
 */
 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Landing_model extends Crud
{
    public function __construct()
    {
        parent::__construct();
    }


    public function getLandingPageByUnique($unique)
    {
        $qweryResult = $this->getFromTableByParams(array('unique' => $unique, 'status' => STATUS_ON), 'landing_page');
        $result      = $qweryResult ? $qweryResult[0] : null;

        return $result;
    }


    public function getLandingRegistredRecipients($landingPageId, $specMailerHistoryDate = null)
    {
        $additionRule = $specMailerHistoryDate ? " AND landing_statistics.date_visited <= '$specMailerHistoryDate'": null;
        $query = $this->db->query("SELECT
                                recipients.name, recipients.email
                            FROM
                                recipients
                            INNER JOIN
                                landing_statistics
                            ON
                                landing_statistics.recipients_id = recipients.id
                            AND
                                landing_statistics.landing_page_id = $landingPageId" . $additionRule);
        return $query->result_array();
    }


    public function getSpecMailerStatistics($landingPageId)
    {
        $query = $this->db->query("SELECT
                                spec_mailer_history.*,
                                landing_page.title AS landing_page_title,
                                article.title AS articles_title
                            FROM
                                spec_mailer_history
                            LEFT JOIN
                                landing_page
                            ON
                                landing_page.id = spec_mailer_history.landing_page_id
                            LEFT JOIN
                                article
                            ON
                                article.id = spec_mailer_history.article_id
                            WHERE
                                spec_mailer_history.landing_page_id = $landingPageId
                            ORDER BY
                                spec_mailer_history.created_at DESC");
        return $query->result_array();
    }


    public function getLandingArticleById($id)
    {
        $qweryResult = $this->getFromTableByParams(array('id' => $id, 'status' => STATUS_ON), 'landing_articles');
        $result      = $qweryResult ? $qweryResult[0] : null;

        return $result;
    }


    public function getLandingArticleData($data)
    {
        $query = $this->db->query("SELECT
                                landing_articles.*
                            FROM
                                landing_articles
                            INNER JOIN
                                landing_statistics
                            ON
                                landing_statistics.landing_page_id = landing_articles.landing_page_id
                            INNER JOIN
                                recipients
                            ON
                                recipients.id = landing_statistics.recipients_id
                            AND
                                recipients.email = '".$data['email']."'
                            WHERE
                                landing_articles.id = ".$data['landing_article_id']."
                                AND
                                landing_articles.landing_page_id = ".$data['landing_page_id']);
        $qweryResult = $query->result_array();
        $result      = $qweryResult ? $qweryResult[0] : null;

        return $result;
    }

}