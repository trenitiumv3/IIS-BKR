<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->helper(array('form', 'url','security','date'));    
        $this->load->model('SupplierModel',"supplierModel"); 
        $this->load->model('PurchaseModel',"PurchaseModel"); 
        $this->load->model('ItemModel',"ItemModel");
        $this->load->model('StockItemModel',"StockItemModel");
        $this->is_logged_in();   
    }

	public function index()
	{                   
        //$this->output->enable_profiler(true);
        $today=date("Y-m-d");
        $data['data_purchase'] = $this->PurchaseModel->getPurchaseByDate($today);
		$data['main_content'] = 'report/report_list_view';                
        $this->load->view('template/template', $data);	
    }

    function goToPurchaseDetail($id){        
        $data['data_purchase'] = $this->PurchaseModel->getPurchaseSummaryById($id);
        $data['data_purchase_detail'] = $this->PurchaseModel->getPurchaseDetail($id);
        $data['main_content'] = 'report/purchase_detail_view';                
        $this->load->view('template/template', $data);	
    }

    function goToHistoryStock($barcode=""){            
        $itemDetail = $this->ItemModel->getItemByBarcode($barcode);
        $id="";
        $itemName="";
        if(!empty($itemDetail)){
            $id=$itemDetail[0]['id'];
            $itemName=$itemDetail[0]['name'];
        }
        $data['barcode'] = $barcode;
        $data['itemName'] = $itemName;
        $data['data_purchase'] = $this->ItemModel->getItemByBarcode($barcode);
        $data['data_purchase_detail'] = $this->StockItemModel->getListStockAdd($id);
        $data['main_content'] = 'report/stock_list_view';                
        $this->load->view('template/template', $data);	
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
