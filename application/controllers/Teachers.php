<?php

defined('BASEPATH') OR exit('No direct script allowed');

Class Teachers extends MY_Controller {

    function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->my_header_view();
        $this->my_table_view('Teachers Data', 'teachers', array(
            'inc' => '#',
            'id' => 'ID',
            'email' => 'Email',
            'fullname' => 'Fullname',
            'option' => 'Option',
        ));
        $this->load->view('bootstrap/footer');
    }

    public function viewreport($t_id) {
        if (is_null($t_id)) {
            show_404();
        }
        $this->my_header_view();
        $this->load->view('view_report');
        $this->load->view('bootstrap/footer');
    }

    public function scheduletoday($t_id = NULL) {
        if (is_null($t_id)) {
            show_404();
        }
        $this->my_header_view();
        $this->load->view('todayschdele_table');
        $this->load->view('bootstrap/footer');
    }

}
