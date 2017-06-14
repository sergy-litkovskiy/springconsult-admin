<?php
 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SaleCategory_model extends Crud
{

    public function __construct()
    {
        parent::__construct();

        $this->idkey = 'id';
        $this->table = 'sale_category';
    }

    public function getCategoryListWithProductList()
    {
        $sql  = $this->_getSqlSelect();

        $this->db->select($sql);

        $this->db->join(
            'sale_category_sale_product_assignment as scspa',
            sprintf('scspa.sale_category_id = %s.id', $this->table),
            'LEFT'
        );

        $this->db->join(
            'sale_product',
            sprintf('sale_product.id = scspa.sale_product_id AND sale_product.status = %s', STATUS_ON),
            'INNER'
        );

        $this->db->join(
            'sale_product_image',
            sprintf(
                'sale_product_image.sale_product_id = sale_product.id AND sale_product_image.is_main = %s',
                STATUS_ON
            ),
            'LEFT'
        );

        $this->db->where([$this->table . '.status' => STATUS_ON]);
        $this->db->order_by($this->table . '.sequence_num, sale_product.sequence_num');

        $query = $this->db->get($this->table);

        return $query->result_array();
    }

    public function getCategoryListWithProductListAdmin()
    {
        $sql = $this->_getSqlSelect();

        $this->db->select($sql);

        $this->db->join(
            'sale_category_sale_product_assignment as scspa',
            sprintf('scspa.sale_category_id = %s.id', $this->table),
            'LEFT'
        );

        $this->db->join(
            'sale_product',
            'sale_product.id = scspa.sale_product_id',
            'LEFT'
        );

        $this->db->join(
            'sale_product_image',
            sprintf(
                'sale_product_image.sale_product_id = sale_product.id AND sale_product_image.is_main = %s',
                STATUS_ON
            ),
            'LEFT'
        );

        $this->db->order_by($this->table . '.sequence_num, sale_product.sequence_num');

        $query = $this->db->get($this->table);

        return $query->result_array();
    }

    private function _getSqlSelect()
    {
        return sprintf('
            %s.*,
            sale_product.id as sale_product_id,
            sale_product.title as sale_product_title,
            sale_product.label as sale_product_label,
            sale_product.status as sale_product_status,
            sale_product.price as sale_product_price,
            sale_product.gift as sale_product_gift,
            sale_product.slug as sale_product_slug,
            sale_product_image.image as sale_product_image,
            sale_product.sequence_num as sale_product_sequence_num,
            sale_product.description as sale_product_description
        ', $this->table);
    }
}