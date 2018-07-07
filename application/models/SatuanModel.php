<?php 
	class SatuanModel extends CI_Model{
        
        var $column_order = array('satuanID','satuanName', 'satuanShortName',null); //set column field database for datatable orderable
        var $column_search = array('satuanName','satuanShortName'); //set column field database for datatable searchable just firstname ,

		function getSatuanList(){
			$this->db->select('*');
            $this->db->from('table_master_satuan a');
            $this->db->where('a.isActive', 1);
            $query = $this->db->get();
            return $query->result_array();
        }

        function getSatuanListData ($searchText,$orderByColumnIndex,$orderDir, $start,$limit){
            $this->_dataSatuanQuery($searchText,$orderByColumnIndex,$orderDir);
            //$this->db->where('a.createdBy',$superUserID);
            // LIMIT
            if($limit!=null || $start!=null){
                $this->db->limit($limit, $start);
            }
            $query = $this->db->get();
            return $query->result_array();
    
        }

        public function count_all(){
            $this->db->from("table_master_satuan a");
            //$this->db->where('a.createdBy',$superUserID);
    
            return $this->db->count_all_results();
        }

        function count_filtered($searchText){
            $this->_dataSatuanQuery($searchText,null,null);
            //$this->db->where('a.createdBy',$superUserID);
    
            $query = $this->db->get();
            return $query->num_rows();
        }
        
        function _dataSatuanQuery($searchText,$orderByColumnIndex,$orderDir){
            $this->db->select('a.satuanID, a.satuanName, a.satuanShortName,
            a.isActive, a.created, a.lastUpdated, a.createdBy, a.lastUpdatedBy');
            $this->db->from('table_master_satuan a');
            
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

		function getSatuanDetail($id){
			$this->db->select('*');
            $this->db->from('table_master_satuan a');           
            $this->db->where('a.isActive', 1);
            $this->db->where('a.satuanID', $id);
            $query = $this->db->get();
            return $query->result_array();	
		}

        function createSatuan($data){
            $this->db->insert('table_master_satuan',$data);
            $result=$this->db->insert_id();
            return $result;
        }

        function updateSatuan($data,$id){
            $this->db->where('satuanID',$id);
            $this->db->update('table_master_satuan',$data);
            $result=$this->db->affected_rows();
            return $result;
        }
        
        function deleteSatuan($id){
            $this->db->where('satuanID',$id);
            $this->db->delete('table_master_satuan');
        }
	}
 ?>