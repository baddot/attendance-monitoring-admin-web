<?php

defined('BASEPATH') or exit('no dirext script allowed');

class Addcourse extends MY_Controller {

    function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->my_header_view();
        $this->load->model('Course_Model');
        $this->Course_Model->form();
        $this->load->view('bootstrap/footer');
    }

}
