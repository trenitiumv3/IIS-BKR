<?php 
	class SupplierModel extends CI_Model{
        
        var $column_order = array('id','name', 'description',null); //set column field database for datatable orderable
        var $column_search = array('name'); //set column field database for datatable searchable just firstname ,

		function getSupplierList(){
			$this->db->select('*');
            $this->db->from('ms_supplier a');
            $this->db->where('a.status', 3);
            $query = $this->db->get();
            return $query->result_array();
        }

        function getSupplierListData ($searchText,$orderByColumnIndex,$orderDir, $start,$limit){
            $this->_dataSupplierQuery($searchText,$orderByColumnIndex,$orderDir);
            //$this->db->where('a.createdBy',$superUserID);
            // LIMIT
            if($limit!=null || $start!=null){
                $this->db->limit($limit, $start);
            }
            $query = $this->db->get();
            return $query->result_array();
    
        }

        public function count_all(){
            $this->db->from("ms_supplier a");
            //$this->db->where('a.createdBy',$superUserID);
    
            return $this->db->count_all_results();
        }

        function count_filtered($searchText){
            $this->_dataSupplierQuery($searchText,null,null);
            //$this->db->where('a.createdBy',$superUserID);
    
            $query = $this->db->get();
            return $query->num_rows();
        }
        
        function _dataSupplierQuery($searchText,$orderByColumnIndex,$orderDir){
            $this->db->select('a.id, a.name, a.description,
            a.status, a.date_created, a.date_updated, a.user_created, a.user_updated');
            $this->db->from('ms_supplier a');
            
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

		function getSupplierDetail($id){
			$this->db->select('*');
            $this->db->from('ms_supplier a');           
            $this->db->where('a.status', 1);
            $this->db->where('a.id', $id);
            $query = $this->db->get();
            return $query->result_array();	
		}

        function createSupplier($data){
            $this->db->insert('ms_supplier',$data);
            $result=$this->db->insert_id();
            return $result;
        }

        function updateSupplier($data,$id){
            $this->db->where('id',$id);
            $this->db->update('ms_supplier',$data);
            $result=$this->db->affected_rows();
            return $result;
        }
        
        function deleteSupplier($id){
            $this->db->where('id',$id);
            $this->db->delete('ms_supplier');
        }
	}
 ?>