<?php
class UserModel extends CI_Model{
    var $column_order = array('id','name', 'username','privilege',null); //set column field database for datatable orderable
    var $column_search = array('name','username','privilege'); //set column field database for datatable searchable just firstname ,

	function getUser($username, $password) {
		$this->db->select('*');
        $this->db->from('ms_user a');
        $this->db->where('a.username', $username);
        $this->db->where('a.password', $password);
        $query = $this->db->get();
        return $query->result_array();
    }

    function getUserDetail($username) {
        $this->db->select('a.username, a.id');
        $this->db->from('ms_user a');
        $this->db->where('a.username', $username);
        //$this->db->where('a.password', $password);
        $query = $this->db->get();
        return $query->result_array();
    }

    function getUserListData ($searchText,$orderByColumnIndex,$orderDir, $start,$limit){
        $this->_dataUserQuery($searchText,$orderByColumnIndex,$orderDir);
        //$this->db->where('a.createdBy',$superUserID);
        // LIMIT
        if($limit!=null || $start!=null){
            $this->db->limit($limit, $start);
        }
        $query = $this->db->get();
        return $query->result_array();

    }

    public function count_all(){
        $this->db->from("ms_user a");
        //$this->db->where('a.createdBy',$superUserID);

        return $this->db->count_all_results();
    }

    function count_filtered($searchText){
        $this->_dataUserQuery($searchText,null,null);
        //$this->db->where('a.createdBy',$superUserID);

        $query = $this->db->get();
        return $query->num_rows();
    }
    
    function _dataUserQuery($searchText,$orderByColumnIndex,$orderDir){
        $this->db->select('a.id, a.username, a.name, 
        a.status, a.date_created, a.date_updated, a.privilege');
        $this->db->from('ms_user a');
        
        //WHERE
        $i = 0;
        if($searchText != null && $searchText != ""){
            //Search By Each Column that define in $column_search
            foreach ($this->column_search as $item){
                // first loop
                if($i===0){
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $searchText);
                }
                else {
                    $this->db->or_like($item, $searchText);
                }

                if(count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket

                $i++;
            }
        }

        //Order by
        if($orderByColumnIndex != null && $orderDir != null ) {
            $this->db->order_by($this->column_order[$orderByColumnIndex], $orderDir);
        }
    }

    function checkUsernameUpdate($username, $id){
        $this->db->select('*');
        $this->db->from('ms_user a');           
        $this->db->where('a.username', $username);
        $this->db->where('a.id !=', $id);
        $query = $this->db->get();
        return $query->result_array();	
    }
    function checkUsernameInsert($username){
        $this->db->select('*');
        $this->db->from('ms_user a');           
        $this->db->where('a.username', $username);            
        $query = $this->db->get();
        return $query->result_array();	
    }

    function createUser($data){
        $this->db->insert('ms_user',$data);
        $result=$this->db->affected_rows();
        return $result;
    }

    function updateUser($data,$id){
        $this->db->where('id',$id);
        $this->db->update('ms_user',$data);
        $result=$this->db->affected_rows();
        return $result;
    }
    
    function deleteSupplier($id){
        $this->db->where('id',$id);
        $this->db->delete('ms_supplier');
    }

    function saveToken($token, $id_user) {
		$updateData=array("token"=>$token);
		$this->db->where("id",$id_user);
		$this->db->update("ms_user",$updateData);  
		return ($this->db->affected_rows() != 1) ? false : true;
    }

    function isToken($token, $username) {
        $this->db->select('a.id');
        $this->db->from('ms_user a');
        $this->db->where('a.token', $token);
        $this->db->where('a.username', $username);
        $query = $this->db->get();
        return $query->result_array();
    }

}
?>