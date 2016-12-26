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


        if ($this->form_validation->run() and ! is_null($this->input->post('btn'))) {
            $this->generate_report($t_id, $this->input->post('month'), $this->input->post('year'));
        }
        if ($this->form_validation->run() and ! is_null($this->input->post('btn2'))) {


            $this->export($t_id, $this->input->post('month'), $this->input->post('year'));
        }

        $this->load->view('view_report_form', array(
            't_id' => $t_id
        ));


        $this->load->view('export_view', array(
            't_id' => $t_id
        ));



        $this->load->view('bootstrap/footer');
    }

    private function data($t_id, $M, $y) {
        
    }

    private function generate_report($t_id, $M, $y) {

        $this->load->model('Schedule_Model');

        $this->load->helper('month');
        $data = array();
        $data['month'] = my_month_array($M);
        $data['year'] = $y;
        $this->load->view('info', $data);

        // $this->my_header_view();
        $this->my_table_view('Report', 'report/' . $t_id . '/' . $M . '/' . $y . '/', array(
            'inc' => '#',
            'date' => 'Date',
            'status' => 'Status',
        ));
        // $this->load->view('bootstrap/footer');


        $this->db->select('*');
        $this->db->where('teacher_id', $t_id);
        $rs = $this->db->get('teacher');
        $f = '';
        if ($rs) {
            $row = $rs->row();
            $f = $row->teacher_fullname;
        }
        $data = array();
        $data['fullname'] = $f;
        $tot = $this->Schedule_Model->total($t_id, $M, $y);
        $data['total'] = $tot['present'];
        $this->load->helper('day');
        $days = working_weekdays_in_month($M, $y);
        $data['totalabsent'] = $tot['absent'];
        $this->load->view('total', $data);
        //   $this->export();
    }

    //  public function export($t_id, $M, $y) {
    public function export() {
        $t_id = $this->input->get('t_id');
        $M = $this->input->get('month');
        $y = $this->input->get('year');
        $this->load->model('Schedule_Model');
        $array_data = $this->Schedule_Model->report_export($t_id, $M, $y);

        $titles = array(
            '#', 'Date', 'Day', 'Status', 'Subject', 'In', 'Out', 'Recorded by Assistant', 'Approved by Teacher', 'Time Recorded',
        );
        $arraaaay = array();
        foreach ($array_data as $k => $v) {
            $arraaaay[] = array(
                $v['inc'], $v['date'], $v['day'],
                $v['status'], $v['subject'], $v['in'],
                $v['out'], $v['recorded_by'], $v['approve_by'], $v['recorded_time']
            );
        }
        $array = array();
        for ($i = 0; $i <= 100; $i++) {
            $array[] = array($i, $i + 1, $i + 2);
        }
        $this->load->library('excel'); //echo print_r($array);
        $teacher_row = $this->db->select('*')->where('teacher_id', $t_id)->get('teacher')->row();
        $this->load->helper('month');
        $this->excel->filename = str_replace(' ', '_', $teacher_row->teacher_fullname . ' ' . my_month_array($M) . ' ' . $y) . '_attendance';
        $this->excel->title_inside = array($teacher_row->teacher_school_id, $teacher_row->teacher_fullname, $teacher_row->teacher_email, my_month_array($M) . ' ' . $y);
        $this->excel->make_from_array($titles, $arraaaay);
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
