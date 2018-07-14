<?php
class UserModel extends CI_Model{

	function getUser($username, $password) {
		$this->db->select('a.username, a.id');
        $this->db->from('ms_user a');
        $this->db->where('a.username', $username);
        $this->db->where('a.password', $password);
        $query = $this->db->get();
        return $query->result_array();
    }

    function saveToken($token, $id_user) {
		$updateData=array("token"=>$token);
		$this->db->where("id",$id_user);
		$this->db->update("ms_user",$updateData);  
		return ($this->db->affected_rows() != 1) ? false : true;
    }

}
?>