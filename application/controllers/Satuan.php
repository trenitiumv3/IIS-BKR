<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Satuan extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->helper(array('form', 'url','security','date'));    
        $this->load->model('SatuanModel',"satuanModel");    
    }

	public function index()
	{
        
		$data['main_content'] = 'master/satuan_list_view';
        $this->sendEmail();
        $this->satuanModel->getSatuanList();
        $this->load->view('template/template', $data);	
    }

    function sendEmail(){
        
        $config = Array
            (
                'protocol' => 'mail',
                'smtp_host' => 'mail.cyberits.co.id',
                'smtp_port' => 465,
                'smtp_user' => 'no-reply@cyberits.co.id',
                'smtp_pass' => 'Pass@word1',
                'mailtype'  => 'html',
                'charset' => 'iso-8859-1',
                'wordwrap' => TRUE
            );  

        $this->email->initialize($config);                   

        $this->load->library('email');

        $this->email->from('no-reply@cyberits.co.id','Feedback System');
        $this->email->to('vickysiswanto@gmail.com');
        
        $this->email->subject('Email Test');
        $this->email->message('Testing the email class.');

        if($this->email->send())
        {
            //echo '1';
            $status = 'Success';
            $msg = 'Please see the detail on your email address.';
        }else{
            show_error($this->email->print_debugger());
            $status = 'failed';
            $msg = 'Thankyou for your message, but we are sorry your message wont reach us any time soon. We will fix it as soon as possible';
        }
    }
    
    function dataSatuanListAjax($superUserID=""){

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

        $result = $this->satuanModel->getSatuanListData($searchText,$orderByColumnIndex,$orderDir, $start,$limit);
        $resultTotalAll = $this->satuanModel->count_all();
        $resultTotalFilter  = $this->satuanModel->count_filtered($searchText);

        $data = array();
        $no = $_POST['start'];
        foreach ($result as $item) {
            $no++;
            $date_created=date_create($item['created']);
            $date_lastModified=date_create($item['lastUpdated']);
            $row = array();
            $row[] = $no;
            $row[] = $item['satuanID'];
            $row[] = $item['satuanName'];
            $row[] = $item['satuanShortName'];
            $row[] = $item['isActive'];
            $row[] = date_format($date_created,"d M Y")." by ".$item['createdBy'];
            $row[] = date_format($date_lastModified,"d M Y")." by ".$item['lastUpdatedBy'];                        
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
}
