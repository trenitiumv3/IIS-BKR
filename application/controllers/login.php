<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->helper(array('form', 'url','security','date'));    
        $this->load->model('UserModel',"loginModel");    
    }

    public function index(){        		
        $this->load->view('login/login_view');	
    }

    public function doLogin(){
        $status = "";
        $msg="";

        //$userLogin = $this->session->userdata('username');        
        $username = $this->security->xss_clean($this->input->post('username'));
        $password = $this->security->xss_clean($this->input->post('password'));

        $query = $this->loginModel->getUser($username,md5($password));        
        if(!empty($query)){
            $status = 'success';
            $msg = "";
            $this->setSessionData($query[0]);
        }else{
            $status = 'error';
            $msg = "Username or Password is Wrong ! ";
        }
        echo json_encode(array('status' => $status, 'msg' => $msg));
    }

    private function setSessionData($userData){        
        $data = array(
            'userID' => $userData['id'],
            'name' => $userData['name'],
            'userName' => $userData['username'],            
            'role' => $userData['privilege'],
            'status' => $userData['status'],
            'token' => $userData['token'],
            'is_logged_in'=>true            
        );
        $this->session->set_userdata($data);
    }

    function is_logged_in(){
        $is_logged_in = $this->session->userdata('is_logged_in');
        if(!isset($is_logged_in) || $is_logged_in != true) {
            $url_login = site_url("Login");
            echo 'You don\'t have permission to access this page. <a href="'.$url_login.'"">Login</a>';
            die();
            $this->load->view('login_form');
        }
    }

	public function logout(){
		$this->session->sess_destroy();
		$this->index();
	}
}
?>