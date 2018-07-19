<?php
class ItemModel extends CI_Model{

    function getItemDetail($id_item) {
        $this->db->select('*');
        $this->db->from('ms_item a');
        $this->db->where('a.id', $id_item);
        $query = $this->db->get();
        return $query->result_array();
    }
    
}
?>