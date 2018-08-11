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

    function getPurchaseByDate($date){
        $this->db->select('a.id, a.total_price, a.type_purchase, a.extra_discount, a.date_created,
        usr.name');
        $this->db->from('tr_purchase_summary a'); 
        $this->db->join('ms_user usr', 'a.user_created=usr.id');          
        $this->db->where(' DATE(a.date_created)', $date);        
        $query = $this->db->get();
        return $query->result_array();	
    }

    function getPurchaseByDatePeriode($startDate, $endDate){
        $this->db->select('a.id, a.total_price, a.type_purchase, a.extra_discount, a.date_created,
        usr.name');
        $this->db->from('tr_purchase_summary a'); 
        $this->db->join('ms_user usr', 'a.user_created=usr.id');          
        $this->db->where(' DATE(a.date_created)>=', $startDate);  
        $this->db->where(' DATE(a.date_created)<=', $endDate);        
        $query = $this->db->get();
        return $query->result_array();	
    }

    function getPurchaseSummaryById($id){
        $this->db->select('a.id, a.total_price, a.type_purchase, a.extra_discount, a.date_created,
        usr.name');
        $this->db->from('tr_purchase_summary a'); 
        $this->db->join('ms_user usr', 'a.user_created=usr.id');          
        $this->db->where('a.id', $id);        
        $this->db->limit(1);
        $query = $this->db->get();
        return $query->row();
    }

    function getPurchaseDetail($transId){
        $this->db->select('count(a.id_item) as stock,a.id, a.id_purchase_summary, a.id_item, a.name_item, a.price_customer,
        a.price_supplier, itm.barcode');
        $this->db->from('tr_purchase a');                 
        $this->db->join('ms_item itm', 'a.id_item=itm.id');   
        $this->db->where('a.id_purchase_summary', $transId);
        $this->db->group_by(array("a.id_purchase_summary", "a.id_item"));         
        $query = $this->db->get();
        return $query->result_array();	
    }

    function getIncomePurchaseByPeriod($startDate, $endDate){
        $this->db->select('date(a.date_created) as tanggal, count(*) as qty, sum(a.price_customer) as total_penjualan, sum(a.price_supplier) as total_modal, sum(a.price_customer) - sum(a.price_supplier) as profit');
        $this->db->from('tr_purchase a');                         
        $this->db->where(' DATE(a.date_created)>=', $startDate);  
        $this->db->where(' DATE(a.date_created)<=', $endDate); 
        $this->db->group_by(array("DATE(a.date_created)"));         
        $query = $this->db->get();

        return $query->row();	
    }

    function getIncomePurchase($date){
        $this->db->select('date(a.date_created) as tanggal, count(*) as qty, sum(a.price_customer) as total_penjualan, sum(a.price_supplier) as total_modal, sum(a.price_customer) - sum(a.price_supplier) as profit');
        $this->db->from('tr_purchase a');                         
        $this->db->where('DATE(a.date_created)', $date);
        $this->db->group_by(array("DATE(a.date_created)"));         
        $query = $this->db->get();

        return $query->row();	
    }

    function getIncomePurchasePerBon($id){
        $this->db->select('sum(a.price_customer) as total_penjualan, sum(a.price_supplier) as total_modal, sum(a.price_customer) - sum(a.price_supplier) as profit');
        $this->db->from('tr_purchase a');                         
        $this->db->where('a.id_purchase_summary', $id);
        $this->db->group_by(array("a.id_purchase_summary"));         
        $query = $this->db->get();

        return $query->row();	
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