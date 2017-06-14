<?php
 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Review_model extends Crud
{
    public function __construct()
    {
        parent::__construct();

        $this->idkey = 'id';
        $this->table = 'review';
    }

    public function getReviewListWithAssignedItemsAdmin()
    {
        $sqlSelect = sprintf(
            '%s.*,
            sale_product.id as sale_product_id,
            sale_product.title as sale_product_title,
            sale_product.label as sale_product_label,
            sale_product.status as sale_product_status,
            
            menu.id as menu_id,
            menu.title as menu_title,
            menu.status as menu_status',
            $this->table
        );

        $this->db->select($sqlSelect);

        $this->db->join(
            'sale_product_review_assignment as spra',
            $this->table . '.id = spra.review_id',
            'LEFT'
        );

        $this->db->join(
            'sale_product',
            'sale_product.id = spra.sale_product_id',
            'LEFT'
        );

        $this->db->join(
            'menu_review_assignment as mra',
            $this->table . '.id = mra.review_id',
            'LEFT'
        );

        $this->db->join(
            'menu',
            'menu.id = mra.menu_id',
            'LEFT'
        );

        $query = $this->db->get($this->table);

        return $query->result_array();
    }

    public function getReviewListBySaleProductId($saleProductId)
    {
        $this->db->select($this->table . '.*');
        $this->db->join(
            'sale_product_review_assignment as spra',
            $this->table . '.id = spra.review_id AND spra.sale_product_id = ' . $saleProductId,
            'INNER'
        );

        $query = $this->db->get($this->table);

        return $query->result_array();
    }
}