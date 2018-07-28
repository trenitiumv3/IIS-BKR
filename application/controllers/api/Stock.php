<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/*
CONTOH REQUEST API

API addStockItem
{
	"username":"admin",
	"token":"842f882962f43c04acc5b3b7b2233c88",
	"id_item":"1",
	"id_supplier":"2",
	"qty_item":"10",
	"price_total_supplier":"105000"
}
*/


class Stock extends CI_Controller {

	function __construct(){
        parent::__construct();
        error_reporting(0);
        $this->load->helper(array('form', 'url','security','date'));
        $this->load->model('UserModel'); 
        $this->load->model('ItemModel'); 
        $this->load->model('StockModel'); 
        $this->load->model('ErrorStatusModel');    
    }

	public function addStockItem() {
		try {
			// RECEIVE REQUEST
			$json = file_get_contents('php://input');
			$dataJsonRequest = json_decode($json);
			// GET JSON REQUEST
			$p_token = $dataJsonRequest->token;
			$p_username = $dataJsonRequest->username;
			$p_id_item = $dataJsonRequest->id_item;
			$p_id_supplier = $dataJsonRequest->id_supplier;
			$p_qty_item = $dataJsonRequest->qty_item;
			$p_price_total_supplier = $dataJsonRequest->price_total_supplier;
			// CEK USER TOKEN VALID
			$this->isUserValidToken($p_token, $p_username);
			// GET USER
	        $result_user = $this->UserModel->getUserDetail($p_username);
			if(empty($result_user)) {
	        	$error_code = -2;
	        	throw new Exception($this->setErrorMessage(-2), -2);
	        }
	        // GET DETAIL ITEM
	        $item_detail = $this->ItemModel->getItemDetail($p_id_item);
	        if (empty($item_detail)) {
	        	throw new Exception($this->setErrorMessage(-4), -4);
	        }
	        // MANIPULATION DATA
	        $current_qty_stock = $item_detail[0]['qty_stock']+$p_qty_item;
	        $data_stock[] = array(	'id_item' => $p_id_item,
					        		'type_trans' => "add_stock",
        							'id_supplier' => $p_id_supplier,
        							'qty_trans' => $p_qty_item,
        							'price_total_supplier' => $p_price_total_supplier,
        							'last_qty_stock' => $item_detail[0]['qty_stock'],
        							'current_qty_stock' => $current_qty_stock,
        							'status' => 0,
        							'user_created' => $result_user[0]['id']
        							);
	        $data_update_master[] = array(	'id' => $p_id_item,
							        		'qty_stock' => $current_qty_stock
		        						);
	        $this->db->trans_start();
	        // MASUKIN STOK BATCH
	        $isSuccess[] = $this->StockModel->addStockBatch($data_stock);
	        $isSuccess[] = $this->StockModel->updateStockMaster($data_update_master);
	        if (in_array(false, $isSuccess)) {
	        	throw new Exception($this->setErrorMessage(-4), -4);
	        }
	        $this->db->trans_complete();	        
			$jsonEncodeResponse = json_encode(array('rescode' => 0,
													'resmessage' => $this->setErrorMessage(0)), JSON_UNESCAPED_SLASHES
												);
		} catch (Exception $e) {
			$error_code = $e->getCode();
			$error_text = $e->getMessage();
			$jsonEncodeResponse = json_encode(array( 	'resCode' => $error_code,
							                            'resMessage' => $error_text
							                        ), JSON_UNESCAPED_SLASHES
												);	
		} finally {
			echo $jsonEncodeResponse;
		}
    }

    private function isUserValidToken($p_token, $p_username) {
		if(empty($this->UserModel->isToken($p_token, $p_username))) {
        	$error_code = -3;
        	throw new Exception($this->setErrorMessage($error_code), $error_code);
        }
    }

    private function getUser($p_username) {
	    $result_user = $this->UserModel->getUserDetail($p_username);
		if(empty($result_user)) {
        	$error_code = -2;
        	throw new Exception($this->setErrorMessage($error_code), $error_code);
        }
        return $result_user;
    }

    private function setErrorMessage($error_code) {
    	try {
	        $result_error = $this->ErrorStatusModel->getErrorDescription($error_code);
	        if (empty($result_error)) {
	        	throw new Exception("Error Processing Request", 1);
	        }
	        return $result_error[0]['description'];
    	} catch(Exception $e) {
    		return "Error Server";
    	}
    }

}
?>