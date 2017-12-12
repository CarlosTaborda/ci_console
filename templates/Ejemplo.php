<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class {{nombre_controlador}} extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model([ '{{nombre_modelo}}' ]);
    }
	public function index()
	{
		echo "Hola mundo";
	}
}