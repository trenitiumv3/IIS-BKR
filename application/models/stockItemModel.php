<?php
class StockItemModel extends CI_Model{

	var $column_order = array('id','name', 'description',null); //set column field database for datatable orderable
    var $column_search = array('name','barcode'); //set column field database for datatable searchable just firstname ,

    function getItemList(){
        $this->db->select('*');
        $this->db->from('tr_stock_item a');
        $this->db->where('a.is_active', 1);
        $query = $this->db->get();
        return $query->result_array();
    }

    function getItemListData ($searchText,$orderByColumnIndex,$orderDir, $start,$limit){
        $this->_dataItemQuery($searchText,$orderByColumnIndex,$orderDir);
        //$this->db->where('a.createdBy',$superUserID);
        // LIMIT
        if($limit!=null || $start!=null){
            $this->db->limit($limit, $start);
        }
        $query = $this->db->get();
        return $query->result_array();

    }

    public function count_all(){
        $this->db->from("tr_stock_item a");
        //$this->db->where('a.createdBy',$superUserID);

        return $this->db->count_all_results();
    }

    function count_filtered($searchText){
        $this->_dataItemQuery($searchText,null,null);
        //$this->db->where('a.createdBy',$superUserID);

        $query = $this->db->get();
        return $query->num_rows();
    }
    
    function _dataItemQuery($searchText,$orderByColumnIndex,$orderDir){
        $this->db->select('a.id, a.name, a.description, a.barcode, a.price_supplier, a.price_customer, a.qty_stock,
        a.status, a.id_user_created, a.id_user_updated, a.date_created, a.date_updated');
        $this->db->from('tr_stock_item a');
        
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
        $this->db->from('tr_stock_item a');           
        $this->db->where('a.is_active', 1);
        $this->db->where('a.id', $id);
        $query = $this->db->get();
        return $query->result_array();	
    }

    function getListStockAdd($id){
        $this->db->select('supp.name as supp_name, a.qty_trans, a.price_total_supplier, a.last_qty_stock, a.current_qty_stock, a.date_created');
        $this->db->from('tr_stock_item a');  
        $this->db->join('ms_supplier supp', 'a.id_supplier=supp.id');           
        $this->db->where('a.type_trans', 'add_stock');
        $this->db->where('a.id_item', $id);
        $this->db->order_by('a.date_created', 'DESC');
        $query = $this->db->get();
        return $query->result_array();	
    }

    function createItem($data){
        $this->db->insert('tr_stock_item',$data);
        $result=$this->db->insert_id();
        return $result;
    }

    function updateItem($data,$id){
        $this->db->where('id',$id);
        $this->db->update('tr_stock_item',$data);
        $result=$this->db->affected_rows();
        return $result;
    }
    
    function deleteItem($id){
        $this->db->where('id',$id);
        $this->db->delete('tr_stock_item');
    }

}
?>