<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Supplier extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->helper(array('form', 'url','security','date'));    
        $this->load->model('SupplierModel',"supplierModel"); 
        $this->is_logged_in();   
    }

	public function index()
	{                   
		$data['main_content'] = 'master/supplier_list_view';        
        $this->supplierModel->getSupplierList();
        $this->load->view('template/template', $data);	
    }

    function dataSupplierListAjax($superUserID=""){

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
            $row[] = $item['id'];
            $row[] = $item['name'];
            $row[] = $item['description'];
            $row[] = $item['status'];
            $row[] = date_format($date_created,"d M Y")." by ".$item['user_created'];
            $row[] = date_format($date_lastModified,"d M Y")." by ".$item['user_updated'];                        
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

    function createSupplier(){
        $status = "";
        $msg="";

        //$userLogin = $this->session->userdata('username');
        $name = $this->security->xss_clean($this->input->post('name'));
        $desc = $this->security->xss_clean($this->input->post('desc'));

        $datetime = date('Y-m-d H:i:s', time());
        $data=array(
            'name'=>$name,
            'description'=>$desc,
            'status'=>3,
            "user_created" => $this->session->userdata('userId'),
			"date_created"=>$datetime,
            "user_updated"=>$this->session->userdata('userId'),
            "date_updated"=>$datetime,
        );
        
        if($this->checkDuplicateMaster("", $name, false)) {
            $this->db->trans_begin();
            $query = $this->supplierModel->createSupplier($data);
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $status = "error";
                $msg="Cannot save master to Database";
            }
            else {
                if($query==1){
                    $this->db->trans_commit();
                    $status = "success";
                    $msg="Supplier berhasil ditambahkan";
                }else{
                    $this->db->trans_rollback();
                    $status = "error";
                    $msg="Terjadi kesalahan saat menyimpan data.. ";
                }
            }
        }else{
            $status = "error";
            $msg="Supplier ".$name." sudah terdaftar !";
        }
        
        echo json_encode(array('status' => $status, 'msg' => $msg));
	}
    
   	function editSupplier(){
        $status = "";
        $msg="";

        $datetime = date('Y-m-d H:i:s', time());
        $id = $this->security->xss_clean($this->input->post('id'));
        $name = $this->security->xss_clean($this->input->post('name'));
        $desc = $this->security->xss_clean($this->input->post('desc'));
        
        $datetime = date('Y-m-d H:i:s', time());
        $data=array(
            'name'=>$name,
            'description'=>$desc,                        
            "user_updated"=>$this->session->userdata('userId'),
            "date_updated"=>$datetime,
        );

        if($this->checkDuplicateMaster($id, $name, true)) {
            $this->db->trans_begin();
            $query = $this->supplierModel->updateSupplier($data, $id);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $status = "error";
                $msg = "Cannot save master to Database";
            } else {
                if ($query == 1) {
                    $this->db->trans_commit();
                    $status = "success";
                    $msg="Supplier berhasil diperbaharui";
                } else {
                    $this->db->trans_rollback();
                    $status = "error";
                    $msg="Terjadi kesalahan saat menyimpan data.. ";
                }
            }
        }else{
            $status = "error";
            $msg="Supplier ".$name." sudah terdaftar !";
        }

        echo json_encode(array('status' => $status, 'msg' => $msg));
    }
    
    private function checkDuplicateMaster($id,$name,$isEdit){
        $query=array();
        if($isEdit){
            $query = $this->supplierModel->checkSupplierNameUpdate($name, $id);
        }else{
            $query = $this->supplierModel->checkSupplierNameInsert($name);
        }
        
        if(empty($query)){
            return true;
        }else{
            return false;
        }
    }

    function deleteSupplier(){
        $status = "";
        $msg="";

        $datetime = date('Y-m-d H:i:s', time());
        $id = $this->security->xss_clean($this->input->post('id'));
        $data=array(            
            'status'=>'4',            
            "date_updated"=>$datetime,
        );

        $this->db->trans_begin();
        $query = $this->supplierModel->updateSupplier($data, $id);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $status = "error";
            $msg = "Cannot save master to Database";
        } else {
            if ($query == 1) {
                $this->db->trans_commit();
                $status = "success";
                $msg="Supplier berhasil diperbaharui";
            } else {
                $this->db->trans_rollback();
                $status = "error";
                $msg="Terjadi kesalahan saat menyimpan data.. ";
            }
        }

        echo json_encode(array('status' => $status, 'msg' => $msg));
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
