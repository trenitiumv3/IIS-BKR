<?php
class ItemModel extends CI_Model{

	var $column_order = array('id','barcode','name','description','qty_stock',"status",null); //set column field database for datatable orderable
    var $column_search = array('name','barcode', 'description'); //set column field database for datatable searchable just firstname ,

    function getItemList(){
        $this->db->select('*');
        $this->db->from('ms_item a');
        $this->db->where('a.status', 3);
        $query = $this->db->get();
        return $query->result_array();
    }

    function getItemByBarcode($barcode) {
        $this->db->select('*');
        $this->db->from('ms_item a');
        $this->db->where('a.barcode', $barcode);
        $query = $this->db->get();
        return $query->result_array();
    }

    function getEditItemByBarcode($barcode, $id){
        $this->db->select('*');
        $this->db->from('ms_item a');
        $this->db->where('a.id !=', $id);
        $this->db->where('a.barcode', $barcode);
        $query = $this->db->get();
        return $query->result_array();
    }

    function updateItemById($data) {
        $this->db->update_batch('ms_item', $data, 'id');
        return ($this->db->affected_rows() >= 1) ? true : false;
    }

    function getAllItem() {
        $this->db->select('*');
        $this->db->from('ms_item a');
        //$this->db->where('a.barcode', $barcode);
        $query = $this->db->get();
        return $query->result_array();
    }

    function getItemListData ($searchText,$orderByColumnIndex,$orderDir, $start,$limit){
        $this->_dataItemQuery($searchText,$orderByColumnIndex,$orderDir);
        $this->db->where('a.status','3');
        // LIMIT
        if($limit!=null || $start!=null){
            $this->db->limit($limit, $start);
        }
        $query = $this->db->get();
        return $query->result_array();
    }

    public function count_all(){
        $this->db->from("ms_item a");
        $this->db->where('a.status','3');
        //$this->db->where('a.createdBy',$superUserID);

        return $this->db->count_all_results();
    }

    function count_filtered($searchText){
        $this->_dataItemQuery($searchText,null,null);
        $this->db->where('a.status','3');
        //$this->db->where('a.createdBy',$superUserID);

        $query = $this->db->get();
        return $query->num_rows();
    }
    
    function _dataItemQuery($searchText,$orderByColumnIndex,$orderDir){
        $this->db->select('a.id, a.name, a.description, a.barcode, a.price_supplier, a.price_customer, a.qty_stock,
        a.status, a.user_created, a.user_updated, a.date_created, a.date_updated');
        $this->db->from('ms_item a');
        
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

    function getItemDetail($id){
        $this->db->select('*');
        $this->db->from('ms_item a');           
        $this->db->where('a.status', 3);
        $this->db->where('a.id', $id);
        $query = $this->db->get();
        return $query->result_array();	
    }

    function createItem($data){
        $this->db->insert('ms_item',$data);
        $result=$this->db->insert_id();
        return $result;
    }

    function updateItem($data,$id){
        $this->db->where('id',$id);
        $this->db->update('ms_item',$data);
        $result=$this->db->affected_rows();
        return $result;
    }
    
    function deleteItem($id){
        $this->db->where('id',$id);
        $this->db->delete('ms_item');
    }	
}
?>