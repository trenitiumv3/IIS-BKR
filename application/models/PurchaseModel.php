<?php
class PurchaseModel extends CI_Model{

	function savePurchaseBatch($data) {
		$this->db->insert_batch('tr_purchase', $data);
        return ($this->db->affected_rows() >= 1) ? true : false;
    }

    function savePurchaseSummary($data) {
        $this->db->insert('tr_purchase_summary', $data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    function isPurchaseStillProcess($id_summary) {
        $this->db->select('a.status');
        $this->db->from('tr_purchase_summary a');
        $this->db->where('a.id', $id_summary);
        //$this->db->where('a.password', $password);
        $query = $this->db->get();
        $arr = $query->result_array();
        if($arr[0]['status'] == 1) {
            return true;
        }
        return false;
    }

    function setSuccessPurchaseSummary($id_summary) {
        $updateData=array("status"=>"0");
        $this->db->where("id",$id_summary);
        $this->db->update("tr_purchase_summary",$updateData);  
        return ($this->db->affected_rows() != 1) ? false : true;
    }

}
?>