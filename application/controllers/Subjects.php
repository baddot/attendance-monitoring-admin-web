<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Subjects extends MY_Controller {

    function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->my_header_view();
        $this->my_table_view('Subjects Data','subjects', array(
            'inc'=>'#',
            'code'=>'Subject Code',
            'description'=>'Subject Description',
            'unit'=>'Unit',
            'option'=>'Option',
        ));
        $this->load->view('bootstrap/footer');
    }

}
