<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Supplier extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->helper(array('form', 'url','security','date'));    
        $this->load->model('SupplierModel',"supplierModel");    
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
            $row[] = $item['supplier_name'];
            $row[] = $item['desc'];
            $row[] = $item['is_active'];
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
            'supplier_name'=>$name,
            'desc'=>$desc,
            'is_active'=>1,
            "user_created" => "sample",
			"date_created"=>$datetime,
            "user_updated"=>"sample",
            "date_updated"=>$datetime,
        );

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

        echo json_encode(array('status' => $status, 'msg' => $msg));
	}
    
   	function editSupplier(){
        $status = "";
        $msg="";

        $datetime = date('Y-m-d H:i:s', time());
        $id = $this->security->xss_clean($this->input->post('id'));
        $name = $this->security->xss_clean($this->input->post('name'));
        // OLD DATA
        $old_data = $this->supplierModel->getDiseaseByID($id);

        $data=array(
            'diseaseName'=>$name,		
			"lastUpdated"=>$datetime,
			"lastUpdatedBy"=>"sample"
        );

        if($this->checkDuplicateMaster($name, true, $old_data->diseaseName)) {
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
                    $msg = "Master Disease has been updated successfully.";
                } else {
                    $this->db->trans_rollback();
                    $status = "error";
                    $msg = "Failed to save data Master ! ";
                }
            }
        }else{
            $status = "error";
            $msg="This ".$name." Disease already exist !";
        }

        echo json_encode(array('status' => $status, 'msg' => $msg));
	}
}
