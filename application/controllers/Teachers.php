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

        $this->load->helper(array('form', 'month', 'combobox'));
        $this->load->library('form_validation');
        $this->form_validation->set_rules(
                array(
                    array(
                        'field' => 'month',
                        'label' => 'Month',
                        'rules' => 'required',
                    ),
                    array(
                        'field' => 'year',
                        'label' => 'Year',
                        'rules' => 'required'
                    ),
                )
        );


        if ($this->form_validation->run()) {
            $this->generate_report($t_id, $this->input->post('month'), $this->input->post('year'));
        }

        $this->load->view('view_report_form', array(
            't_id' => $t_id
        ));



        $this->load->view('bootstrap/footer');
    }

    private function generate_report($t_id, $M, $y) {

        $this->load->helper('day');

        $this->db->select('*');
        $this->db->where('teacher_id', $t_id);
        $this->db->like('attendance_date', $y . ':' . $M);
        $rs = $this->db->get('attendance');

        $days = working_weekdays_in_month($M, $y);
        $days_present = array();
        if ($rs) {
            foreach ($rs->result() as $row) {
                // $days_present[] = ;

                list($y, $m, $d) = explode(':', $row->attendance_date);

                if (in_array($d, $days)) {
                    $days_present[] = $d;
                }
            }
        }

        $this->load->view('view_report_result', array(
            //  'data' => '[' . $this->db->last_query() . ']'
            'data' => $days,
            'data2' => $days_present
        ));
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
