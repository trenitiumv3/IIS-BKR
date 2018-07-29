<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/*
CONTOH REQUEST API

API getItemDetailByBarcode
{
	"username":"admin",
	"token":"93c0f5f7746a7b04b77ca5f7937362f0",
	"data": {
	  	"barcode":"2134uhsdf84"
  	}
}

API getAllListItem
{
	"username":"admin",
	"token":"93c0f5f7746a7b04b77ca5f7937362f0"
}

API updateItem
{
	"username":"admin",
	"token":"805e5a8c7eb7517f863718274716a734",
  	"data": {
      "id":1,
      "name":"Indomie test",
      "description":"Indomie telor bakso test"
  	}
}
*/


class Item extends CI_Controller {

	function __construct(){
        parent::__construct();
        error_reporting(0);
        $this->load->helper(array('form', 'url','security','date'));
        $this->load->model('UserModel'); 
        $this->load->model('ItemModel'); 
        $this->load->model('ErrorStatusModel');    
    }

	public function getItemDetailByBarcode() {
		try {
			// RECEIVE REQUEST
			$json = file_get_contents('php://input');
			$dataJsonRequest = json_decode($json);
			// GET JSON REQUEST
			$p_token = $dataJsonRequest->token;
			$p_username = $dataJsonRequest->username;
			$p_barcode = $dataJsonRequest->data->barcode;

			// CEK USER TOKEN VALID
			$this->isUserValidToken($p_token, $p_username);
			// GET USER
	        $result_user = $this->UserModel->getUserDetail($p_username);
			if(empty($result_user)) {
	        	$error_code = -2;
	        	throw new Exception($this->setErrorMessage(-2), -2);
	        }
	        // GET DATA DETAIL BY BARCODE
	        $result_item = $this->ItemModel->getItemByBarcode($p_barcode);

	        if(empty($result_item)) {
	        	$error_code = 2;
	        	throw new Exception($this->setErrorMessage($error_code), $error_code);
	        }

	        /*
	        $result_id = $result_item[0]['id'];
	        $result_name = $result_item[0]['name'];
	        $result_description = $result_item[0]['description'];
	        $result_price_supplier = $result_item[0]['price_supplier'];
	        $result_price_customer = $result_item[0]['price_customer'];
	        $result_qty_stock = $result_item[0]['qty_stock'];
	        $result_status = $result_item[0]['status'];
	        $result_user_created = $result_item[0]['user_created'];
	        $result_user_updated = $result_item[0]['user_updated'];
	        */
			
			$jsonEncodeResponse = json_encode(array('rescode' => 0,
													'data' => $result_item[0],
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

    public function getAllListItem() {
		try {
			// RECEIVE REQUEST
			$json = file_get_contents('php://input');
			$dataJsonRequest = json_decode($json);
			// GET JSON REQUEST
			$p_token = $dataJsonRequest->token;
			$p_username = $dataJsonRequest->username;
			// CEK USER TOKEN VALID
			$this->isUserValidToken($p_token, $p_username);
			// GET USER
	        $result_user = $this->UserModel->getUserDetail($p_username);
			if(empty($result_user)) {
	        	$error_code = -2;
	        	throw new Exception($this->setErrorMessage(-2), -2);
	        }
	        // GET ALL DATA
	        $result_item = $this->ItemModel->getAllItem();

	        if(empty($result_item)) {
	        	$error_code = 2;
	        	throw new Exception($this->setErrorMessage($error_code), $error_code);
	        }
	        /*
	        $result_id = $result_item[0]['id'];
	        $result_name = $result_item[0]['name'];
	        $result_description = $result_item[0]['description'];
	        $result_price_supplier = $result_item[0]['price_supplier'];
	        $result_price_customer = $result_item[0]['price_customer'];
	        $result_qty_stock = $result_item[0]['qty_stock'];
	        $result_status = $result_item[0]['status'];
	        $result_user_created = $result_item[0]['user_created'];
	        $result_user_updated = $result_item[0]['user_updated'];
	        */
			$jsonEncodeResponse = json_encode(array('rescode' => 0,
													'data' => $result_item,
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

    public function updateItem() {
		try {
			// RECEIVE REQUEST
			$json = file_get_contents('php://input');
			$dataJsonRequest = json_decode($json);
			// GET JSON REQUEST
			$p_token = $dataJsonRequest->token;
			$p_username = $dataJsonRequest->username;
			$p_data_update = $dataJsonRequest->data;
			// CEK USER TOKEN VALID
			$this->isUserValidToken($p_token, $p_username);
			// GET USER
	        $result_user = $this->UserModel->getUserDetail($p_username);
			if(empty($result_user)) {
	        	$error_code = -2;
	        	throw new Exception($this->setErrorMessage(-2), -2);
	        }
	        // UPDATE ITEM
	        $data_update_master[] = $p_data_update;
	        if(!$this->ItemModel->updateItemById($data_update_master)) {
	        	throw new Exception($this->setErrorMessage(2), 2);
			}
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