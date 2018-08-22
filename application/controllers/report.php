<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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

	public function index(){                   
        //$this->output->enable_profiler(true);
        $today=date("Y-m-d");
        $data['data_purchase'] = $this->PurchaseModel->getPurchaseByDate($today);
        $data['data_income'] = $this->PurchaseModel->getIncomePurchase($today);
		$data['main_content'] = 'report/report_list_view';                
        $this->load->view('template/template', $data);	
    }

    public function download()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Hello World !');
        
        $writer = new Xlsx($spreadsheet);
 
        $filename = 'name-of-the-generated-file';
 
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output'); // download file 
 
    }

    public function goToReportRange($startDate="",$endDate=""){                   
        //$this->output->enable_profiler(true);
        $today=date("Y-m-d");
        $data['data_purchase'] = $this->PurchaseModel->getPurchaseByDatePeriode($startDate,$endDate);
        $data['data_income'] = $this->PurchaseModel->getIncomePurchaseByPeriod($startDate,$endDate);
        $data['startDate'] = $startDate;
        $data['endDate'] = $endDate;
		$data['main_content'] = 'report/report_range_list_view';                
        $this->load->view('template/template', $data);	
    }

    function goToPurchaseDetail($id){        
        $data['data_purchase'] = $this->PurchaseModel->getPurchaseSummaryById($id);
        $data['data_income'] = $this->PurchaseModel->getIncomePurchasePerBon($id);
        $data['data_purchase_detail'] = $this->PurchaseModel->getPurchaseDetail($id);
        $data['main_content'] = 'report/purchase_detail_view';                
        $this->load->view('template/template', $data);	
    }

    function goToHistoryStock($barcode=""){            
        $itemDetail = $this->ItemModel->getItemByBarcode($barcode);
        $id="";
        $itemName="";
        $stock="";
        if(!empty($itemDetail)){
            $id=$itemDetail[0]['id'];
            $itemName=$itemDetail[0]['name'];
            $stock=$itemDetail[0]['qty_stock'];
        }
        $data['barcode'] = $barcode;
        $data['itemName'] = $itemName;
        $data['stock'] = $stock;
        $data['data_purchase'] = $this->ItemModel->getItemByBarcode($barcode);
        $data['data_purchase_detail'] = $this->StockItemModel->getListStockAdd($id);
        $data['main_content'] = 'report/stock_list_view';                
        $this->load->view('template/template', $data);	
    }

    function goToPurchaseItem($startDate="",$endDate=""){
        //$this->output->enable_profiler(TRUE);
        $today=date("Y-m-d");
        $data['data_purchase'] = $this->PurchaseModel->getItemPurchaseByPeriod($startDate,$endDate);
        
        $data['startDate'] = $startDate;
        $data['endDate'] = $endDate;
		$data['main_content'] = 'report/purchase_item_list_view';                
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
