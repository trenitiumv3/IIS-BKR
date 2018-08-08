<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->helper(array('form', 'url','security','date'));    
        $this->load->model('UserModel',"UserModel"); 
        $this->is_logged_in();   
    }

	public function index()
	{                   
		$data['main_content'] = 'master/user_list_view';        
        $this->load->view('template/template', $data);	
    }

    function dataUserListAjax($superUserID=""){

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

        $result = $this->UserModel->getUserListData($searchText,$orderByColumnIndex,$orderDir, $start,$limit);
        $resultTotalAll = $this->UserModel->count_all();
        $resultTotalFilter  = $this->UserModel->count_filtered($searchText);

        $data = array();
        $no = $_POST['start'];
        foreach ($result as $item) {
            $no++;
            $date_created=date_create($item['date_created']);
            $date_lastModified=date_create($item['date_updated']);
            $row = array();
            $row[] = $no;
            $row[] = $item['id'];
            $row[] = $item['name'];
            $row[] = $item['username'];
            $row[] = $item['privilege'];            
            $row[] = date_format($date_created,"d M Y");
            $row[] = date_format($date_lastModified,"d M Y");                        
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

    function createUser(){
        $status = "";
        $msg="";

        //$userLogin = $this->session->userdata('username');
        $name = $this->security->xss_clean($this->input->post('name'));
        $username = $this->security->xss_clean($this->input->post('username'));
        $password = $this->security->xss_clean($this->input->post('password'));
        $role = $this->security->xss_clean($this->input->post('role'));

        $datetime = date('Y-m-d H:i:s', time());
        $data=array(
            'name'=>$name,
            'username'=>$username,
            'password'=>md5($password),
            'privilege'=>$role,
            'status'=>3,            
			"date_created"=>$datetime,            
            "date_updated"=>$datetime,
        );
        
        if($this->checkDuplicateMaster("", $username, false)) {
            $this->db->trans_begin();
            $query = $this->UserModel->createUser($data);
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $status = "error";
                $msg="Cannot save master to Database";
            }
            else {
                if($query==1){
                    $this->db->trans_commit();
                    $status = "success";
                    $msg="User berhasil ditambahkan";
                }else{
                    $this->db->trans_rollback();
                    $status = "error";
                    $msg="Terjadi kesalahan saat menyimpan data.. ";
                }
            }
        }else{
            $status = "error";
            $msg="Username ".$username." sudah terdaftar !";
        }
        
        echo json_encode(array('status' => $status, 'msg' => $msg));
	}
    
   	function editUser(){
        $status = "";
        $msg="";

        $datetime = date('Y-m-d H:i:s', time());
        $id = $this->security->xss_clean($this->input->post('id'));
        $name = $this->security->xss_clean($this->input->post('name'));
        $username = $this->security->xss_clean($this->input->post('username'));
        $chpass = $this->security->xss_clean($this->input->post('chpass'));        
        $role = $this->security->xss_clean($this->input->post('role'));
        $password = $this->security->xss_clean($this->input->post('password'));
        
        $data=array();
        $datetime = date('Y-m-d H:i:s', time());
        if($chpass==true){
            $data=array(
                'name'=>$name,
                'username'=>$username,
                'password'=>md5($password),
                'privilege'=>$role,                                           
                "date_updated"=>$datetime,
            );
        }else{
            $data=array(
                'name'=>$name,
                'username'=>$username,                
                'privilege'=>$role,                                           
                "date_updated"=>$datetime,
            );
        }

        if($this->checkDuplicateMaster($id, $username, true)) {
            $this->db->trans_begin();
            $query = $this->UserModel->updateUser($data, $id);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $status = "error";
                $msg = "Cannot save master to Database";
            } else {
                if ($query == 1) {
                    $this->db->trans_commit();
                    $status = "success";
                    $msg="User berhasil diperbaharui";
                } else {
                    $this->db->trans_rollback();
                    $status = "error";
                    $msg="Terjadi kesalahan saat menyimpan data.. ";
                }
            }
        }else{
            $status = "error";
            $msg="Username ".$name." sudah terdaftar !";
        }

        echo json_encode(array('status' => $status, 'msg' => $msg));
    }
    
    private function checkDuplicateMaster($id,$username,$isEdit){
        $query=array();
        if($isEdit){
            $query = $this->UserModel->checkUsernameUpdate($username, $id);
        }else{
            $query = $this->UserModel->checkUsernameInsert($username);
        }
        
        if(empty($query)){
            return true;
        }else{
            return false;
        }
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
