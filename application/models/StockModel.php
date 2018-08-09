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

    function getPurchaseDetail($itemId){
        $this->db->select('a.qty_trans, a.price_total_supplier, a.last_qty_stock, a.current_qty_stock,
        a.price_supplier, sup.name as sup_name, usr.name as usr_name');
        $this->db->from('tr_stock_item a');                 
        $this->db->join('ms_supplier sup', 'a.id_supplier=sup.id');  
        $this->db->join('ms_user usr', 'a.user_created=usr.id');     
        $this->db->where('a.id_item', $itemId);
        $this->db->where('a.type_trans', "add_stock");               
        $query = $this->db->get();
        return $query->result_array();	
    }

}
?>