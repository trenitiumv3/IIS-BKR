<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Item extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->helper(array('form', 'url','security','date'));  
        $this->load->model('ItemModel',"ItemModel");
        $this->load->model('StockModel',"StockModel");
        $this->load->model('ItemDiscountModel',"ItemDiscountModel");  
        $this->is_logged_in();              
    }

	public function index(){        
		$data['main_content'] = 'master/item_list_view';             
        $this->load->view('template/template', $data);	
    }

    public function goToAddItem(){        
		$data['main_content'] = 'master/item_add_view';                
        $this->load->view('template/template', $data);	
    }
 
    public function goToEditItem($itemId){                        
        $item_detail = $this->ItemModel->getItemDetail($itemId);
        if (!empty($item_detail)) {            
            $discount_list = $this->ItemDiscountModel->getDiscountByItem($itemId);

            $data['main_content'] = 'master/item_edit_view';
            $data['item'] = $item_detail[0];    
            $data['discount'] = $discount_list;                
            $this->load->view('template/template', $data);	
        }else{
            $data['main_content'] = 'template/page_404';
            $data['msg'] = 'Item tidak ditemukan';
            $this->load->view('template/template', $data);	
        }		
    }

    function dataItemListAjax($superUserID=""){

        //Check Super Admin Clinic
        $role = $this->session->userdata('role');        

        $searchText = $this->security->xss_clean($_POST['search']['value']);
        $limit = $_POST['length'];
        $start = $_POST['start'];

        // here order processing
        if(isset($_POST['order'])){
            $orderByColumnIndex = $_POST['order']['0']['column'];
            $orderDir =  $_POST['order']['0']['dir'];
        }
        else {
            $orderByColumnIndex = 1;
            $orderDir = "ASC";
        }

        $result = $this->ItemModel->getItemListData($searchText,$orderByColumnIndex,$orderDir, $start,$limit);
        $resultTotalAll = $this->ItemModel->count_all();
        $resultTotalFilter  = $this->ItemModel->count_filtered($searchText);

        $data = array();
        $no = $_POST['start'];
        foreach ($result as $item) {
            $no++;
            $date_created=date_create($item['date_created']);
            $date_lastModified=date_create($item['date_updated']);
            $row = array();
            $row[] = $no;
            $row[] = $item['barcode'];
            $row[] = $item['name'];
            $row[] = $item['description'];
            $row[] = $item['qty_stock'];
            $row[] = $item['status'];
            $row[] = date_format($date_created,"d M Y")." by ".$item['user_created'];
            $row[] = date_format($date_lastModified,"d M Y")." by ".$item['user_updated'];
            $row[] = $item['id'];
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $resultTotalAll,
            "recordsFiltered" => $resultTotalFilter,
            "data" => $data,
        );

        //$this->output->enable_profiler(TRUE);
        //output to json format
        echo json_encode($output);
    }

    public function addNewStock(){        
		$data['main_content'] = 'master/stock_add_view';                
        $this->load->view('template/template', $data);	
    }
    public function editItem(){        
		$status = "";
        $msg="";

        //$userLogin = $this->session->userdata('username');
        $barcode = $this->security->xss_clean($this->input->post('barcode'));
        $itemName = $this->security->xss_clean($this->input->post('name'));
        $qtyStock = $this->security->xss_clean($this->input->post('qty_stock'));
        $itemId = $this->security->xss_clean($this->input->post('id'));
        $priceSupplier = $this->security->xss_clean($this->input->post('price_supplier'));
        $desc = $this->security->xss_clean($this->input->post('description'));
        $itemList = json_decode($this->security->xss_clean($this->input->post('item_price_list')));
        $priceCustomer=$this->security->xss_clean($this->input->post('price_customer'));

        // BEGIN ADD
        $this->db->trans_begin();

        $datetime = date('Y-m-d H:i:s', time());
        $dataNewItem=array(
            'name'=>$itemName,
            'description'=>$desc,
            'barcode'=>$barcode,            
            'price_customer'=>$priceCustomer,
			'price_supplier'=>$priceSupplier,
            'qty_stock'=>$qtyStock,
            'status'=>3,            
            "user_updated"=>$this->session->userdata('userId'),
            "date_updated"=>$datetime,
        );

        //Duplicate Barcode
        $checkBarcode = $this->ItemModel->getEditItemByBarcode($barcode,$itemId);        
        if(count($checkBarcode)==0){
            $affected_row = $this->ItemModel->updateItem($dataNewItem, $itemId);
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $status = "error";
                $msg="Cannot save master to Database, Error When Save Item";
            }else{                         
                $itemDiscount = array();     
                $itemDiscountUpdate = array();     
                //is_array($itemList);
                foreach($itemList as $row){                    
                    if($row->oldData){
                        if($row->del == "true"){
                            // Delete Discount
                            $this->ItemDiscountModel->deleteDiscount($row->id);                        
                        }else{
                            // Update Discount
                            $priceDiscount=array(                            
                                'qty_for_discount'=>$row->qty,
                                'discount'=>$row->price
                            );
                            $this->ItemDiscountModel->updateDiscount($priceDiscount,$row->id);                        
                        }                        
                    }else{
                        // Add Discount
                        $priceDiscount=array(
                            'id_item'=>$itemId,
                            'qty_for_discount'=>$row->qty,
                            'discount'=>$row->price
                        );
                        array_push($itemDiscount, $priceDiscount);
                    }                    
                }

                $isSuccessDiscount = true;
                if(!empty($itemDiscount)){
                    // Add Discount
                    $isSuccessDiscount = $this->ItemDiscountModel->addDiscountBatch($itemDiscount);
                }
                
                if($isSuccessDiscount){
                    $this->db->trans_commit();
                    $status = "success";
                    $msg="Item berhasil diupdate";
                }else{
                    $this->db->trans_rollback();
                    $status = "error";
                    $msg="Cannot save master to Database, Error When Save Price";
                }                        
            }
        }else{
            $status = "error";
            $msg="Barcode ini sudah terdaftar";
        }
        echo json_encode(array('status' => $status, 'msg' => $msg));  
    }
    public function getItemDetailByBarcode($barcode){
        $status = "";
        $msg="";
        $content="";
        $data = $this->ItemModel->getItemByBarcode($barcode);
        if(count($data) > 0){
            $status = "success";
            $msg="Data found";
            $content=array(
                'name'=>$data[0]['name'],
                'description'=>$data[0]['description'],
                'barcode'=>$data[0]['barcode'],
                'id'=>$data[0]['id']
            );
        }else{
            $status = "error";
            $msg="Data not found";
        }
        echo json_encode(array('status' => $status, 'msg' => $msg, 'content'=>$content)); 
    }

    public function insertItem(){
        $status = "";
        $msg="";

        //$userLogin = $this->session->userdata('username');
        $barcode = $this->security->xss_clean($this->input->post('barcode'));
        $itemName = $this->security->xss_clean($this->input->post('name'));
        $qtyStock = $this->security->xss_clean($this->input->post('qty_stock'));
        $supplier = $this->security->xss_clean($this->input->post('supplier'));
        $priceSupplier = $this->security->xss_clean($this->input->post('price_supplier'));
        $desc = $this->security->xss_clean($this->input->post('description'));
        $itemList = json_decode($this->security->xss_clean($this->input->post('item_price_list')));
        $priceCustomer=$this->security->xss_clean($this->input->post('price_customer'));

        // BEGIN ADD
        $this->db->trans_begin();

        $datetime = date('Y-m-d H:i:s', time());
        $dataNewItem=array(
            'name'=>$itemName,
            'description'=>$desc,
            'barcode'=>$barcode,
            'price_supplier'=>$priceSupplier,
            'price_customer'=>$priceCustomer,
            'qty_stock'=>$qtyStock,
            'status'=>3,
            "user_created" => $this->session->userdata('userId'),
			"date_created"=>$datetime,
            "user_updated"=>$this->session->userdata('userId'),
            "date_updated"=>$datetime,
        );

        //Duplicate Barcode
        $checkBarcode = $this->ItemModel->getItemByBarcode($barcode);        
        if(count($checkBarcode)==0){
            $id_item = $this->ItemModel->createItem($dataNewItem);
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $status = "error";
                $msg="Cannot save master to Database, Error When Save Item";
            }else{
                // Add Stock Batch
                $price_total_supplier = $qtyStock * $priceSupplier;
                $data_stock[] = array(	
                    'id_item' => $id_item,
                    'type_trans' => "add_stock",
                    'id_supplier' => $supplier,
                    'qty_trans' => $qtyStock,
                    'price_total_supplier' => $price_total_supplier,
                    'last_qty_stock' => 0,
                    'current_qty_stock' => 0,
                    'status' => 0,
                    'user_created' => $this->session->userdata('userId')            
                );
                // MASUKIN STOK BATCH
                $isSuccess = $this->StockModel->addStockBatch($data_stock);            
                if($isSuccess){
                    $itemDiscount = array();     
                    //is_array($itemList);
                    foreach($itemList as $row){                        
                        $priceDiscount=array(
                            'id_item'=>$id_item,
                            'qty_for_discount'=>$row->qty,
                            'discount'=>$row->price
                        );
                        array_push($itemDiscount, $priceDiscount);
                    }

                    $isSuccessDiscount = true;
                    if(!empty($itemDiscount)){
                        $isSuccessDiscount = $this->ItemDiscountModel->addDiscountBatch($itemDiscount);
                    }
                    
                    if($isSuccessDiscount){
                        $this->db->trans_commit();
                        $status = "success";
                        $msg="Item berhasil ditambahkan";
                    }else{
                        $this->db->trans_rollback();
                        $status = "error";
                        $msg="Cannot save master to Database, Error When Save Price";
                    }
                }else{
                    $this->db->trans_rollback();
                    $status = "error";
                    $msg="Cannot save master to Database, Error When Save Stock";
                }                        
            }
        }else{
            $status = "error";
            $msg="Barcode ini sudah terdaftar";
        }
        echo json_encode(array('status' => $status, 'msg' => $msg));                                        
    }

    function dataSatuanListAjax($superUserID=""){

        //Check Super Admin Clinic
        $role = $this->session->userdata('role');        

        $searchText = $this->security->xss_clean($_POST['search']['value']);
        $limit = $_POST['length'];
        $start = $_POST['start'];

        // here order processing
        if(isset($_POST['order'])){
            $orderByColumnIndex = $_POST['order']['0']['column'];
            $orderDir =  $_POST['order']['0']['dir'];
        }
        else {
            $orderByColumnIndex = 1;
            $orderDir = "ASC";
        }

        $result = $this->satuanModel->getSatuanListData($searchText,$orderByColumnIndex,$orderDir, $start,$limit);
        $resultTotalAll = $this->satuanModel->count_all();
        $resultTotalFilter  = $this->satuanModel->count_filtered($searchText);

        $data = array();
        $no = $_POST['start'];
        foreach ($result as $item) {
            $no++;
            $date_created=date_create($item['created']);
            $date_lastModified=date_create($item['lastUpdated']);
            $row = array();
            $row[] = $no;
            $row[] = $item['satuanID'];
            $row[] = $item['satuanName'];
            $row[] = $item['satuanShortName'];
            $row[] = $item['isActive'];
            $row[] = date_format($date_created,"d M Y")." by ".$item['createdBy'];
            $row[] = date_format($date_lastModified,"d M Y")." by ".$item['lastUpdatedBy'];                        
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $resultTotalAll,
            "recordsFiltered" => $resultTotalFilter,
            "data" => $data,
        );

        //$this->output->enable_profiler(TRUE);
        //output to json format
        echo json_encode($output);
    }

    function insertStockItem(){
        $status = "";
        $msg="";

        //$userLogin = $this->session->userdata('username');
        $datetime = date('Y-m-d H:i:s', time());
        $itemId = $this->security->xss_clean($this->input->post('item_id'));        
        $qtyStock = $this->security->xss_clean($this->input->post('qty_stock'));
        $supplier = $this->security->xss_clean($this->input->post('supplier'));
        $priceSupplier = $this->security->xss_clean($this->input->post('price_supplier'));                

        $item_detail = $this->ItemModel->getItemDetail($itemId);
        if (empty($item_detail)) {
            $status = "error";
            $msg="Item tidak temukan..";
        }else{
            $current_qty_stock = $item_detail[0]['qty_stock']+$qtyStock;
            // BEGIN ADD
            $this->db->trans_begin();
            $data_stock[] = array(	'id_item' => $itemId,
                'type_trans' => "add_stock",
                'id_supplier' => $supplier,
                'qty_trans' => $qtyStock,
                'price_total_supplier' => $priceSupplier,
                'last_qty_stock' => $item_detail[0]['qty_stock'],
                'current_qty_stock' => $current_qty_stock,
                'status' => 0,
                'user_created' =>  $this->session->userdata('userId')
            );
            $data_update_master[] = array(	'id' => $itemId,
                'price_supplier' => $priceSupplier,
				'qty_stock' => $current_qty_stock,
                'user_updated'=> $this->session->userdata('userId'),
                'date_updated'=> $datetime
            );
            $isSuccess[] = $this->StockModel->addStockBatch($data_stock);
            $isSuccess[] = $this->ItemModel->updateItemById($data_update_master);
            if (in_array(false, $isSuccess)) {
                $this->db->trans_rollback();
                $status = "error";
                $msg="Cannot add Stock Item..";
            }else{
                $this->db->trans_commit();
                $status = "success";
                $msg="Stok berhasil ditambahkan";
            }            
        }                            
        echo json_encode(array('status' => $status, 'msg' => $msg));  
    }

    function checkBarcode($barcode){
        $data = $this->ItemModel->getItemByBarcode($barcode);
        if(count($data)==0){            
            $status = "success";
            $msg="Barcode berhasil di generate";
        }else{
            $status = "error";
            $msg="Barcode ini sudah terdaftar";
        }        
        echo json_encode(array('status' => $status, 'msg' => $msg));                                        
    }

    function is_logged_in(){
        $is_logged_in = $this->session->userdata('is_logged_in');
        if(!isset($is_logged_in) || $is_logged_in != true) {
            $url_login = site_url("Login");
            echo 'You don\'t have permission to access this page. <a href="'.$url_login.'"">Login</a>';
            die();
            redirect("login/index");
        }
    }
}
