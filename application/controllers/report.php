<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Report extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->helper(array('form', 'url','security','date','download'));    
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

    public function downloadReportToday(){
        $today=date("Y-m-d");
        $totalPurchase=0;
        $totalCash=0;
        $totalDebit=0;
        $countDebit=0;
        $countCash=0;
        $modalAll=0;
        $data_purchase = $this->PurchaseModel->getPurchaseByDate($today);
        $data_income = $this->PurchaseModel->getIncomePurchase($today);
        
        $date1=date_create($today);        

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'LAPORAN PENJUALAN PER HARI');
        $sheet->setCellValue('B1', date_format($date1,"d F Y"));

        $sheet->setCellValue('A2', "Tanggal Transaksi");
        $sheet->setCellValue('B2', "ID Transaksi");
        $sheet->setCellValue('C2', "Jenis Pembayaran");
        $sheet->setCellValue('D2', "Harga");
        $sheet->setCellValue('E2', "Discount");
        $sheet->setCellValue('F2', "Total");
        $sheet->setCellValue('G2', "Kasir");

        if(isset($data_income->total_modal)){
            $modalAll=$data_income->total_modal==""?0:$data_income->total_modal;
        }                                    
        $indexCount=3;
        foreach($data_purchase as $row){
            $discount= $row['extra_discount']==""?0:$row['extra_discount'];
            $finalPrice=($row['total_price']-($row['total_price']*$discount/100));
            $totalPurchase += $finalPrice;
            if($row['type_purchase']=="cash"){
                $totalCash+=$finalPrice;
                $countCash++;
            }else if($row['type_purchase']=="debit"){
                $totalDebit+=$finalPrice;
                $countDebit++;
            }
            
            $sheet->setCellValue('A'.$indexCount, $row['date_created']);
            $sheet->setCellValue('B'.$indexCount, $row['id']);
            $sheet->setCellValue('C'.$indexCount, $row['type_purchase']);
            $sheet->setCellValue('D'.$indexCount, $row['total_price']);
            $sheet->setCellValue('E'.$indexCount, $discount);
            $sheet->setCellValue('F'.$indexCount, $finalPrice);
            $sheet->setCellValue('G'.$indexCount, $row['name']);
            $indexCount++;
        }                
        
        $indexCount++;
        $sheet->setCellValue('A'.$indexCount,"Total Transaksi Cash");
        $sheet->setCellValue('B'.$indexCount, $countCash);
        $indexCount++;
        $sheet->setCellValue('A'.$indexCount,"Total Transaksi Debit");
        $sheet->setCellValue('B'.$indexCount, $countDebit);
        $indexCount++;
        $sheet->setCellValue('A'.$indexCount,"Total Transaksi");
        $sheet->setCellValue('B'.$indexCount, count($data_purchase));
        $indexCount++;
        $sheet->setCellValue('A'.$indexCount,"Total Penjualan Cash");
        $sheet->setCellValue('B'.$indexCount, $totalCash);
        $indexCount++;
        $sheet->setCellValue('A'.$indexCount,"Total Penjualan Debit");
        $sheet->setCellValue('B'.$indexCount, $totalDebit);
        $indexCount++;
        $sheet->setCellValue('A'.$indexCount,"Total Penjualan");
        $sheet->setCellValue('B'.$indexCount, intval($totalPurchase));
        $indexCount++;
        $sheet->setCellValue('A'.$indexCount,"Total Harga Supplier");
        $sheet->setCellValue('B'.$indexCount, intval($modalAll));
        $indexCount++;
        $sheet->setCellValue('A'.$indexCount,"Total Keuntungan");
        $sheet->setCellValue('B'.$indexCount, intval($totalPurchase-$modalAll));
        $indexCount++;
        
        $writer = new Xlsx($spreadsheet);
 
        $filename = 'Laporan_Penjualan_Tanggal_'.$today;
 
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

    public function downloadReportRange($startDate="",$endDate=""){
        $totalPurchase=0;
        $totalCash=0;
        $totalDebit=0;
        $countDebit=0;
        $countCash=0;
        $modalAll=0;
        $data_purchase = $this->PurchaseModel->getPurchaseByDatePeriode($startDate,$endDate);
        $data_income = $this->PurchaseModel->getIncomePurchaseByPeriod($startDate,$endDate);
        
        $date1=date_create($startDate);
        $date2=date_create($endDate);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'LAPORAN PENJUALAN');
        $sheet->setCellValue('B1', date_format($date1,"d F Y"));
        $sheet->setCellValue('C1', date_format($date2,"d F Y"));

        $sheet->setCellValue('A2', "Tanggal Transaksi");
        $sheet->setCellValue('B2', "ID Transaksi");
        $sheet->setCellValue('C2', "Jenis Pembayaran");
        $sheet->setCellValue('D2', "Harga");
        $sheet->setCellValue('E2', "Discount");
        $sheet->setCellValue('F2', "Total");
        $sheet->setCellValue('G2', "Kasir");

        foreach($data_income as $row){
            $modalAll+=($row['total_modal']); 
        }

        $indexCount=3;
        foreach($data_purchase as $row){
            $discount= $row['extra_discount']==""?0:$row['extra_discount'];
            $finalPrice=($row['total_price']-($row['total_price']*$discount/100));
            $totalPurchase += $finalPrice;
            if($row['type_purchase']=="cash"){
                $totalCash+=$finalPrice;
                $countCash++;
            }else if($row['type_purchase']=="debit"){
                $totalDebit+=$finalPrice;
                $countDebit++;
            }
            
            $sheet->setCellValue('A'.$indexCount, $row['date_created']);
            $sheet->setCellValue('B'.$indexCount, $row['id']);
            $sheet->setCellValue('C'.$indexCount, $row['type_purchase']);
            $sheet->setCellValue('D'.$indexCount, $row['total_price']);
            $sheet->setCellValue('E'.$indexCount, $discount);
            $sheet->setCellValue('F'.$indexCount, $finalPrice);
            $sheet->setCellValue('G'.$indexCount, $row['name']);
            $indexCount++;
        }                
        
        $indexCount++;
        $sheet->setCellValue('A'.$indexCount,"Total Transaksi Cash");
        $sheet->setCellValue('B'.$indexCount, $countCash);
        $indexCount++;
        $sheet->setCellValue('A'.$indexCount,"Total Transaksi Debit");
        $sheet->setCellValue('B'.$indexCount, $countDebit);
        $indexCount++;
        $sheet->setCellValue('A'.$indexCount,"Total Transaksi");
        $sheet->setCellValue('B'.$indexCount, count($data_purchase));
        $indexCount++;
        $sheet->setCellValue('A'.$indexCount,"Total Penjualan Cash");
        $sheet->setCellValue('B'.$indexCount, $totalCash);
        $indexCount++;
        $sheet->setCellValue('A'.$indexCount,"Total Penjualan Debit");
        $sheet->setCellValue('B'.$indexCount, $totalDebit);
        $indexCount++;
        $sheet->setCellValue('A'.$indexCount,"Total Penjualan");
        $sheet->setCellValue('B'.$indexCount, intval($totalPurchase));
        $indexCount++;
        $sheet->setCellValue('A'.$indexCount,"Total Harga Supplier");
        $sheet->setCellValue('B'.$indexCount, intval($modalAll));
        $indexCount++;
        $sheet->setCellValue('A'.$indexCount,"Total Keuntungan");
        $sheet->setCellValue('B'.$indexCount, intval($totalPurchase-$modalAll));
        $indexCount++;
        

        $writer = new Xlsx($spreadsheet);
 
        $filename = 'Laporan_Penjualan_Periode_'.$startDate.'_'.$endDate;
 
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output'); // download file 
 
    }

    function goToPurchaseDetail($id,$startDate="",$endDate="",$periode=""){        
        $data['startDate'] = $startDate;
        $data['endDate'] = $endDate;
        $data['isPeriod'] = $periode;
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
        $data['data_purchase'] = array();
        if($startDate!="" && $endDate!=""){
            $data['data_purchase'] = $this->PurchaseModel->getItemPurchaseByPeriod($startDate,$endDate);
        }        
        
        $data['startDate'] = $startDate;
        $data['endDate'] = $endDate;
		$data['main_content'] = 'report/purchase_item_list_view';                
        $this->load->view('template/template', $data);	
        
    }

    public function downloadReportPurchaseItem($startDate="",$endDate=""){
        $date1=date_create($startDate);
        $date2=date_create($endDate);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'LAPORAN PENJUALAN BARANG');
        $sheet->setCellValue('B1', date_format($date1,"d F Y"));
        $sheet->setCellValue('C1', date_format($date2,"d F Y"));

        $sheet->setCellValue('A2', "Nama Barang");
        $sheet->setCellValue('B2', "Total Qty");
        $sheet->setCellValue('C2', "Total Harga Modal");
        $sheet->setCellValue('D2', "Total Harga Jual");
        $sheet->setCellValue('E2', "Keuntungan");        

        $data_purchase = $this->PurchaseModel->getItemPurchaseByPeriod($startDate,$endDate);
        $indexCount=3;
        foreach($data_purchase as $row){      
            $sheet->setCellValue('A'.$indexCount, $row['name']);
            $sheet->setCellValue('B'.$indexCount, $row['qty']);
            $sheet->setCellValue('C'.$indexCount, $row['total_modal']);
            $sheet->setCellValue('D'.$indexCount, $row['total_penjualan']);
            $sheet->setCellValue('E'.$indexCount, $row['profit']);
            $indexCount++;
        }

        $writer = new Xlsx($spreadsheet);
 
        $filename = 'Laporan_Penjualan_Barang_Tanggal_'.$startDate.'_'.$endDate;        
 
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output'); // download file 
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
