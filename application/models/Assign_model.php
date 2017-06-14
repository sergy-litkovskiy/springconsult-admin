<?php
/**
 * @author Litkovsky
 * @copyright 2010
 * model for index page
 */
 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Assign_model extends Crud
{
    private $assignsArr;

    public function __construct()
    {
        parent::__construct();
        $this->assignsArr = array(
            'newSourceIdArr'    => null
            ,'oldSourceIdArr'   => null
            ,'assignId'         => null
            ,'assignFieldName'  => null
            ,'sourceFieldName'  => null
            ,'table'            => null);
    }


    public function setAssignArr($assignsArr)
    {
        $this->assignsArr = $assignsArr;
    }


    public function addOrDeleteAssigns()
    {
        $newAssignSourceIdArr         = $this->_prepareNewAssignedIdArrIndexIsEqualValue();
        $clearedNewAssignSourceIdArr  = $this->_deleteClearedAssigns($newAssignSourceIdArr);
        $clearedNewAssignSourceIdArr ? $this->_addNewAssigns($clearedNewAssignSourceIdArr) : null;
    }


    private function _prepareNewAssignedIdArrIndexIsEqualValue()
    {
        $newAssignSourceIdArr = array();

        foreach($this->assignsArr['newSourceIdArr'] as $assignId){
            $newAssignSourceIdArr[$assignId] = $assignId;
        }

        return $newAssignSourceIdArr;
    }


    private function _deleteClearedAssigns($newAssignSourceIdArr)
    {
        foreach($this->assignsArr['oldSourceIdArr'] as $oldAssignSourceId){
            if(!in_array($oldAssignSourceId, $newAssignSourceIdArr)){
                $this->_delAssignedFromTableByParams($this->assignsArr, $oldAssignSourceId);
            } else{
                unset($newAssignSourceIdArr[$oldAssignSourceId]);
            }
        }

        return $newAssignSourceIdArr;
    }


    private function _addNewAssigns($clearedNewAssignSourceIdArr)
    {
        foreach($clearedNewAssignSourceIdArr as $newAssignSourceId){
            $data = array($this->assignsArr['sourceFieldName'] => $newAssignSourceId, $this->assignsArr['assignFieldName'] => $this->assignsArr['assignId']);
            $this->index_model->addInTable($data, $this->assignsArr['table']);
        }
    }


    protected function _delAssignedFromTableByParams($assignsArr, $oldAssignSourceId)
    {
        $this->db->where($assignsArr['assignFieldName'], $assignsArr['assignId']);
        $this->db->where($assignsArr['sourceFieldName'], $oldAssignSourceId);
        if(!$this->db->delete($assignsArr['table']))
        {
            return false;
        }
        return true;
    }
}