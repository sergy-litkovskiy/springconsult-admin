<?php
 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SaleProductImage_model extends Crud
{

    public function __construct()
    {
        parent::__construct();

        $this->idkey = 'id';
        $this->table = 'sale_product_image';
    }

    public function getSaleProductImageBySaleProductId($saleProductId, $mainOnly = false)
    {
        $this->db->select($this->table . '.*');
        $this->db->where(['sale_product_id' => $saleProductId]);

        if ($mainOnly) {
            $this->db->where(['is_main' => STATUS_ON]);
        }

        $this->db->order_by($this->table . '.sequence_num');

        $query = $this->db->get($this->table);

        return $query->result_array();
    }
}