<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	function __construct(){
        parent::__construct();
        error_reporting(0);
        $this->load->helper(array('form', 'url','security','date'));
        //$this->load->database();
        $this->load->model('UserModel'); 
        $this->load->model('ErrorStatusModel');    
    }

	public function loginUser() {
		try {
			// RECEIVE REQUEST
			$json = file_get_contents('php://input');
			$dataJsonRequest = json_decode($json);
			// GET JSON REQUEST
			$p_username = $dataJsonRequest->username;
			$p_password = $dataJsonRequest->password;
			// MANIPULATION VARIABLE
			$md5_password = md5($p_password);
			// GET MODEL
	        $result_user = $this->UserModel->getUser($p_username, $md5_password);
	        // JIKA USER TIDAK ADA
	        if(empty($result_user)) {
	        	$error_code = -2;
	        	throw new Exception($this->setErrorMessage($error_code), $error_code);
	        }
	        // GENERATE TOKEN
			$generate_token = MD5($p_password.date("Y-m-d h:i:s").$p_username);
			// SAVE TOKEN
			$id_user = $result_user[0]['id'];
			$result_token = $this->UserModel->saveToken($generate_token, $id_user);
			if(!$result_token) {
	        	$error_code = 2;
	        	throw new Exception($this->setErrorMessage($error_code), $error_code);	
			}
			$jsonEncodeResponse = json_encode(array('token' => $generate_token,
													'rescode' => 0,
													'resmessage' => $this->setErrorMessage(0)), JSON_UNESCAPED_SLASHES
												);
		} catch (Exception $e) {
			$jsonEncodeResponse = json_encode(array( 	'resCode' => $e->getCode(),
							                            'resMessage' => $e->getMessage()
							                        ), JSON_UNESCAPED_SLASHES
												);	
		} finally {
			echo $jsonEncodeResponse;
		}

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