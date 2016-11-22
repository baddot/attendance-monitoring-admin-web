<?php

defined('BASEPATH') or exit('no script allowed');

class Addteacher extends MY_Controller {

    function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->my_header_view();
        $this->load->model('Teacher_Model');
        $this->Teacher_Model->add();
        $this->load->view('bootstrap/footer');
    }

}
