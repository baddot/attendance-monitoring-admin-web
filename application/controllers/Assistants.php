<?php

defined('BASEPATH') or exit('mo direct script allowed');

class Assistants extends MY_Controller
{

        function __construct()
        {
                parent::__construct();
        }

        public function index()
        {
                $this->my_header_view();
                $this->my_table_view('Assistants Data', 'assistants', array(
                    'inc'      => '#',
                    'fullname' => 'Fullname',
                    'email'    => 'Email',
                    'status'   => 'Status',
                    'option'   => 'Option',
                ));
                $this->load->view('bootstrap/footer');
        }

        public function update($assistant_id)
        {
                $this->my_header_view();
                $this->load->model('Assistant_Model');
                $this->Assistant_Model->form($assistant_id);
                $this->load->view('bootstrap/footer');
        }

}
