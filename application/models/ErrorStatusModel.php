<?php
class ErrorStatusModel extends CI_Model{

	function getErrorDescription($code) {
		$this->db->select('a.description');
        $this->db->from('ms_status_code a');
        $this->db->where('a.id', $code);
        $query = $this->db->get();
        return $query->result_array();
    }

}
?>