<?php

defined('BASEPATH') or exit('nodirect script allowed');

class Addassistant extends MY_Controller
{

        function __construct()
        {
                parent::__construct();
        }

        public function index()
        {
                $this->my_header_view();
                $this->load->model('Assistant_Model');
                $this->Assistant_Model->form();
                $this->load->view('bootstrap/footer');
        }

}
