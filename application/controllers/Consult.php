<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Consult extends CI_Controller {

	public function index()
	{
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
		header("Access-Control-Allow-Headers: X-Requested-With, Content-Type");
		header('Content-Type: application/json');

		//$params = $this->input->post('');
		//$params = json_decode($params);
		//echo json_encode($params);

		$inputJSON = file_get_contents('php://input');
		$input = json_decode($inputJSON, TRUE);

		echo json_encode($input['todo']);
	}
}
