<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/*
CONTOH REQUEST API

API purchaseOrder
{
  "username": "cashier",
  "token": "16f84e4d9621344988291f081a358bbd",
  "data": {
    "buyer_name": "Andi Sitanggang",
    "type_purchase": "cash",
    "total_price": 35600
  }
}

API purchaseConfirm
{
  "username": "admin",
  "token": "1bc8c2afc4f8e6bd68122207d79e3348",
  "data": {
    "id_purchase_summary": 7,
    "array_item_purchase": [
      {
        "id_item": 2,
        "name_item": "supermie",
        "price_customer": 11800,
        "price_supplier": 9500
      },
      {
        "id_item": 2,
        "name_item": "supermie",
        "price_customer": 11800,
        "price_supplier": 9500
      },
      {
        "id_item": 1,
        "name_item": "indomie",
        "price_customer": 12000,
        "price_supplier": 10000
      }
    ]
  }
}
*/


class Purchase extends CI_Controller {

	function __construct(){
        parent::__construct();
        error_reporting(0);
        $this->load->helper(array('form', 'url','security','date'));
        $this->load->model('UserModel'); 
        $this->load->model('ItemModel'); 
        $this->load->model('PurchaseModel');
        $this->load->model('StockModel');
        $this->load->model('ErrorStatusModel');    
    }

	public function purchaseOrder() {
		try {
			// RECEIVE REQUEST
			$json = file_get_contents('php://input');
			$dataJsonRequest = json_decode($json);
			// GET JSON REQUEST
			$p_token = $dataJsonRequest->token;
			$p_username = $dataJsonRequest->username;
			$p_buyer_name = $dataJsonRequest->data->buyer_name;
			$p_type_purchase = $dataJsonRequest->data->type_purchase;
			$p_total_price_customer = $dataJsonRequest->data->total_price;
	        // CEK USER TOKEN VALID
			$this->isUserValidToken($p_token, $p_username);
			// GET USER
	        $result_user = $this->UserModel->getUserDetail($p_username);
			if(empty($result_user)) {
	        	$error_code = -2;
	        	throw new Exception($this->setErrorMessage(-2), -2);
	        }
	        // MANIPULATION DATA
	        $dataPurchaseSummary = array(	"total_price" => $p_total_price_customer,
	        								"buyer_name" => $p_buyer_name,
	        								"type_purchase" => $p_type_purchase,
	        								"status" => 1,
	        								"user_created" => $result_user[0]['id']
	    								);
	        // MASUKIN PURCHASE SUMMARY
	        $this->PurchaseModel->savePurchaseSummary($dataPurchaseSummary);

	        // ID PURCHASE SUMMARY

			$jsonEncodeResponse = json_encode(array('rescode' => 0,
													'resmessage' => $this->setErrorMessage(0)), JSON_UNESCAPED_SLASHES
												);
		} catch (Exception $e) {
			$error_code = $e->getCode();
			$error_text = $e->getMessage();
			$jsonEncodeResponse = json_encode(array( 	'rescode' => $error_code,
							                            'resmessage' => $error_text
							                        ), JSON_UNESCAPED_SLASHES
												);	
		} finally {
			echo $jsonEncodeResponse;
		}

    }

    public function purchaseConfirm() {
		try {
			// RECEIVE REQUEST
			$json = file_get_contents('php://input');
			$dataJsonRequest = json_decode($json);
			// GET JSON REQUEST
			$p_token = $dataJsonRequest->token;
			$p_username = $dataJsonRequest->username;
			$p_id_purchase_summary = $dataJsonRequest->data->id_purchase_summary;
			$p_arr_item_purchase = $dataJsonRequest->data->array_item_purchase;
	        // CEK USER TOKEN VALID
			$this->isUserValidToken($p_token, $p_username);
			// GET USER
	        $result_user = $this->getUser($p_username);
	        // CEK JIKA SUMMARY MASIH PROSES ATAU BELUM
	        if(!$this->PurchaseModel->isPurchaseStillProcess($p_id_purchase_summary)) {
	        	throw new Exception($this->setErrorMessage(-4), -4);
	        }
			// MANIPULASI DATA ARR
			$total_price_customer = 0;
	        foreach ($p_arr_item_purchase as $key => $value) {
	        	$data_purchase[] = array(	'id_purchase_summary' => $p_id_purchase_summary,
	        								'id_item' => $value->id_item,
	        								'name_item' => $value->name_item,
	        								'price_customer' => $value->price_customer,
	        								'price_supplier' => $value->price_supplier,
	        								'status' => 0,
	        								'user_created' => $result_user[0]['id']
	        							);
	        	if(!isset($count_stock[$value->id_item])) {
	        		$count_stock[$value->id_item] = 1;
	        	} else {
	        		$count_stock[$value->id_item] += 1;
	        	}
	        }
	        foreach ($count_stock as $id_item => $qty_item) {
	        	$item_detail = $this->ItemModel->getItemDetail($id_item);
	        	$last_qty_stock = $item_detail[0]['qty_stock'];
	        	$current_qty_stock = $last_qty_stock - $qty_item;
	        	$data_stock[] = array(	'id_item' => $id_item,
						        		'type_trans' => "reduce_stock",
	        							'id_supplier' => "1",
	        							'qty_trans' => "-".$qty_item,
	        							'price_total_supplier' => "-",
	        							'last_qty_stock' => $last_qty_stock,
	        							'current_qty_stock' => $current_qty_stock,
	        							'status' => 0,
	        							'user_created' => $result_user[0]['id']
	        							);
	        	$data_update_master[] = array(	'id' => $id_item,
								        		'qty_stock' => $current_qty_stock
			        						);
	        }
	        $this->db->trans_start();
	        // UPDATE SUMMARY
	        $isSuccess[] = $this->PurchaseModel->setSuccessPurchaseSummary($p_id_purchase_summary);
	        // MASUKAN SEMUA DATA KE PURCHASE
	        $isSuccess[] = $this->PurchaseModel->savePurchaseBatch($data_purchase);
	        // KURANGIN STOK
	        $isSuccess[] = $this->StockModel->reduceStockBatch($data_stock);
	       	// UPDATE STOK DI MASTER ITEM
	        $isSuccess[] = $this->ItemModel->updateItemById($data_update_master);
	        // CEK JIKA ADA YANG TIDAK BERHASIL
	        if (in_array(false, $isSuccess)) {
	        	throw new Exception($this->setErrorMessage(-4), -4);
	        }
	        $this->db->trans_complete();
			$jsonEncodeResponse = json_encode(array('rescode' => 0,
													'resmessage' => $this->setErrorMessage(0)), JSON_UNESCAPED_SLASHES
												);
		} catch (Exception $e) {
			$jsonEncodeResponse = json_encode(array( 	'rescode' => $e->getCode(),
							                            'resmessage' => $e->getMessage()
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