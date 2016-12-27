<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Subjects extends MY_Controller {

    function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->my_header_view();
        $this->my_table_view('Subjects Data', 'subjects', array(
            'inc' => '#',
            'code' => 'Subject Code',
            'description' => 'Subject Description',
            'unit' => 'Unit',
            'option' => 'Option',
        ));
        $this->load->view('bootstrap/footer');
    }

    public function update($subject_id = NULL) {
        if (is_null($subject_id)) {
            show_error('Invalid');
        }
        $this->my_header_view();
        $this->load->model('Subject_Model');
        $this->Subject_Model->form($subject_id);
        $this->load->view('bootstrap/footer');
    }

}
