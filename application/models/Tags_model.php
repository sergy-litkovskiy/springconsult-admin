<?php
/**
 * @author Litkovsky
 * @copyright 2010
 *
 */

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @property mixed tags_model
 * @property mixed index_model
 */
class Tags_model extends Crud
{
    protected $params, $tableName;
    public $idkey;

    public function __construct()
    {
        parent::__construct();
        $this->idkey;
        $this->tableName;
    }


    public function findTagMasterIdByDescription($description)
    {
        $qwery = $this->db->query("SELECT id FROM tag_master WHERE description = '" . $description . "'");

        $result = $qwery->result_array();

        return $result ? $result[0]['id'] : null;
    }


    public function deleteArticlesTagByTagIdAndArticlesId($tagMasterId, $id)
    {
        return $this->db->query("DELETE FROM articles_tag WHERE tag_master_id = " . $tagMasterId . " AND article_id = " . $id . "");
    }


    public function getCloudsTag()
    {
        $tagMaster    = $this->db->query("SELECT
                                            tag_master.*
                                            , COUNT(articles_tag.id) AS articles_tag_amount
                                      FROM
                                            tag_master
                                      LEFT JOIN 
                                            articles_tag 
                                      ON
                                            articles_tag.tag_master_id = tag_master.id
                                      INNER JOIN 
                                            article 
                                      ON
                                            article.id = articles_tag.article_id  
                                      WHERE
                                            article.status = " . STATUS_ON . "
                                      GROUP BY
                                            tag_master.id");
        $arrTagMaster = $tagMaster->result_array();

        return $this->_prepareCloudsTag($arrTagMaster);
    }


    private function _prepareCloudsTag($arrTagMaster)
    {
        list($min, $max) = $this->_getMinMaxCountTags($arrTagMaster);
        return $this->_prepareArrCloudsTag($arrTagMaster, $min, $max);
    }


    private function _getMinMaxCountTags($arrTagMaster)
    {
        $min = $max = 0;
        for ($i = 1; $i < count($arrTagMaster); $i++) {
            if ($arrTagMaster[$i]['articles_tag_amount'] > $max) {
                $max = $arrTagMaster[$i]['articles_tag_amount'];
            }
            if ($arrTagMaster[$i]['articles_tag_amount'] < $min) {
                $min = $arrTagMaster[$i]['articles_tag_amount'];
            }
        }

        return array($min, $max);
    }


    private function _prepareArrCloudsTag($arrTagMaster, $min, $max)
    {
        $fontSize = 0;
        $minSize  = 70;
        $maxSize  = 150;

        foreach ($arrTagMaster as $key => $tag) {
            if ($min == $max) {
                $fontSize = round(($maxSize - $minSize) / 2 + $minSize);
            } else {
                $fontSize = round((($tag['articles_tag_amount'] - $min) / ($max - $min)) * ($maxSize - $minSize) + $minSize);
            }
            $arrTagMaster[$key]['font_size'] = $fontSize;
        }
        return $arrTagMaster;
    }


    //////////////TAG PROCESS/////////////
    public function tagProcess($arrAssignedTags, $id, $table, $key)
    {
        $this->idkey     = $key;
        $this->tableName = $table;
        $newTags         = array();
        $oldTags         = array();

        foreach ($arrAssignedTags as $tagMasterId => $tagDescription) {
            if (preg_match('/(\d+)\-a$/', $tagMasterId, $matches)) {
                $oldTags[$matches[1]] = $tagDescription;
            } else {
                $newTags[] = $tagDescription;
            }
        }

        if (!$this->delFromTable($id, $this->tableName)) {
            throw new Exception('Not deleted assigned tags form '.$this->tableName.' table');
        }

        if (count($newTags)) {
            foreach ($newTags as $tag) {
                $this->_addNewTagProcess($tag, $id);
            }
        }

        if (count($oldTags)) {
            foreach ($oldTags as $tagMasterId => $tagDescription) {
                $this->_addAssignedTagProcess($tagMasterId, $tagDescription, $id);
            }
        }
    }


    private function _addNewTagProcess($newTag, $id)
    {
        $tagMasterId    = $this->index_model->addInTable(array('description' => $newTag), 'tag_master');
        $arrAssignedTag = $this->_prepareArrAssignedTag($tagMasterId, $id);
        $assignedTagId  = $this->index_model->addInTable($arrAssignedTag, $this->tableName);

        Common::assertTrue($assignedTagId, 'Not inserted new assign tag into ' . $this->tableName);
    }


    private function _addAssignedTagProcess($tagMasterId, $tagDescription, $id)
    {
        $this->index_model->updateInTable($tagMasterId, array('description' => $tagDescription), 'tag_master');

        $arrAssignedTag = $this->_prepareArrAssignedTag($tagMasterId, $id);
        $assignedTagId  = $this->index_model->addInTable($arrAssignedTag, $this->tableName);

        Common::assertTrue($assignedTagId, 'Not inserted assigned tag into ' . $this->tableName);
    }


    private function _prepareArrAssignedTag($tagMasterId, $id)
    {
        $arrAssignedTag                  = array();
        $arrAssignedTag['tag_master_id'] = $tagMasterId;
        $arrAssignedTag[$this->idkey]      = $id;

        return $arrAssignedTag;
    }

}