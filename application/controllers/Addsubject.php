<?php

defined('BASEPATH') or exit('no dirext script allowed');

class Addsubject extends MY_Controller
{

        function __construct()
        {
                parent::__construct();
        }

        public function index()
        {
                $this->my_header_view();
                $this->load->model('Subject_Model');
                $this->Subject_Model->form();
                $this->load->view('bootstrap/footer');
        }

}
