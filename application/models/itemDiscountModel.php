<?php
class ItemDiscountModel extends CI_Model{

	function reduceStockBatch($data) {
        $this->db->insert_batch('tr_stock_item', $data);
        return ($this->db->affected_rows() >= 1) ? true : false;
    }

    function addDiscountBatch($data) {
        $this->db->insert_batch('item_discount', $data);
        return ($this->db->affected_rows() >= 1) ? true : false;
    }

    function updateDiscount($data,$id){
        $this->db->where('id',$id);
        $this->db->update('item_discount',$data);
        $result=$this->db->affected_rows();
        return $result;
    }
    
    function deleteDiscountByItem($id){
        $this->db->where('id_item',$id);
        $this->db->delete('item_discount');
        $result=$this->db->affected_rows();
        return $result;
    }

    function deleteDiscount($id){
        $this->db->where('id',$id);
        $this->db->delete('item_discount');
        $result=$this->db->affected_rows();
        return $result;
    }

    function getDiscountByItem($itemId) {
        $this->db->select('*');
        $this->db->from('item_discount a');
        $this->db->where('a.id_item', $itemId);
        $this->db->order_by("a.qty_for_discount", "ASC");
        $query = $this->db->get();
        return $query->result_array();
    }
}
?>