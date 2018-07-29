<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Item extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->helper(array('form', 'url','security','date'));  
        $this->load->model('ItemModel',"itemModel");  
        $this->is_logged_in();              
    }

	public function index(){        
		$data['main_content'] = 'master/item_add_view';        
        
        $this->load->view('template/template', $data);	
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

        $result = $this->supplierModel->getSupplierListData($searchText,$orderByColumnIndex,$orderDir, $start,$limit);
        $resultTotalAll = $this->supplierModel->count_all();
        $resultTotalFilter  = $this->supplierModel->count_filtered($searchText);

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
            $row[] = $item['status'];
            $row[] = date_format($date_created,"d M Y")." by ".$item['id_user_created'];
            $row[] = date_format($date_lastModified,"d M Y")." by ".$item['id_user_updated'];                        
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
		$data['main_content'] = 'master/item_edit_view';                
        $this->load->view('template/template', $data);	
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
        $priceCustomer="";

        var_dump($desc);        
        //is_array($itemList);
        foreach($itemList as $row){
            if($row->qty == 1){
                $priceCustomer=$row->price;
            }
            echo $row->qty;
            echo $row->price;
        }

        $datetime = date('Y-m-d H:i:s', time());
        $dataItem=array(
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
        $id_item = $this->ItemModel->createItem($dataNewItem);
        
        // Add Stock Batch
        $price_total_supplier = $p_new_data->qty_stock * $p_new_data->price_supplier;
	    $data_stock[] = array(	
            'id_item' => $id_item,
            'type_trans' => "add_stock",
            'id_supplier' => $p_id_supplier,
            'qty_trans' => $p_new_data->qty_stock,
            'price_total_supplier' => $price_total_supplier,
            'last_qty_stock' => 0,
            'current_qty_stock' => $p_new_data->qty_stock,
            'status' => 0,
            'user_created' => $p_new_data->user_created
        );
	    // MASUKIN STOK BATCH
	    $isSuccess[] = $this->StockModel->addStockBatch($data_stock);
        $id_item = $this->ItemModel->createItem($dataNewItem);

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
