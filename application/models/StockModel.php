<?php
class StockModel extends CI_Model{

	function reduceStockBatch($data) {
        $this->db->insert_batch('tr_stock_item', $data);
        return ($this->db->affected_rows() >= 1) ? true : false;
    }

    function addStockBatch($data) {
        $this->db->insert_batch('tr_stock_item', $data);
        return ($this->db->affected_rows() >= 1) ? true : false;
    }

}
?>