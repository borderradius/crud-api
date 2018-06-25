<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {
	public function index()
	{
        
    }

    /**
     * 유저 CRUD API
     *
     * @return void
     */
    public function rest()
    {
		header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");

        $method = $_SERVER['REQUEST_METHOD'];
        $this->load->model('api_m');
        
        // For POST
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        // For Parameter
        $param = array(
            'seq' => $this->input->get('seq') , 
            'pageNum' => $this->input->get('pageNum') ,
            'keyword' => $this->input->get('keyword') ,
            'oicode' => $this->input->get('oicode')
        );

        switch ($method) {
            case 'POST':
            $result = $this->api_m->createConsult($request);
            break;
            case 'GET':
            $result = $this->api_m->getConsult($param);
            break;
            case 'DELETE':
            $result = $this->api_m->deleteConsult($param['seq']);
            break;
            case 'PUT':
            $result = $this->api_m->modifyConsult($request);
            break;
        }
        
        echo json_encode($result,JSON_NUMERIC_CHECK);
    }


    // 삼성점 데스크탑 상담신청
    public function restpc()
    {
        $this->load->model('api_m');
        $this->load->helper('url');
        $result = $this->api_m->createConsultPcSs($_POST);
        if ($result) {
            redirect("http://trinitycare.co.kr/",'location');
            
        }
    }
    // 청담점 데스크탑 상담신청
    public function restpccd()
    {
        $this->load->model('api_m');
        $this->load->helper('url');
        $result = $this->api_m->createConsultPcCd($_POST);
        if ($result) {
            redirect("http://trinitycare.co.kr/",'location');
            
        }
    }




    /**
     * 관리자 CRUD API
     *
     * @return void
     */
    public function restAdmin()
    {
		header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");

        $method = $_SERVER['REQUEST_METHOD'];
        $this->load->model('api_m');
        
        // For POST
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        
        switch ($method) {
            // case 'POST':
            // $result = $this->api_m->createConsult($request);
            // break;
            // case 'GET':
            // $result = $this->api_m->getConsult($param);
            // break;
            // case 'DELETE':
            // $result = $this->api_m->deleteConsult($param['seq']);
            // break;
            case 'PUT':
            $result = $this->api_m->registReply($request); 
            break;
        }
        
        echo json_encode($result,JSON_NUMERIC_CHECK);

    }


    


}
