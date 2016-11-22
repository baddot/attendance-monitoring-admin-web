<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {

    function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->my_header_view();
        // $this->load->view('dashboard_top');
        $this->load->view('calendar');
        $this->load->view('bootstrap/footer');
    }

    function logout() {
        $this->session->unset_userdata('logged_in');
        session_destroy();
        redirect('login', 'refresh');
    }

}
