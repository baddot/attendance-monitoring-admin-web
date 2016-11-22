<?php

defined('BASEPATH') or exit('no direct script allowed');

class Subject_Model extends MY_Model {

    function __construct() {
        parent::__construct();
    }

    public function getSubjectForCombo() {
        $this->db->select('subject_id,subject_desc');
        $rs = $this->db->get('subject');
        $data = array();
        foreach ($rs->result() as $val) {
            $data[$val->subject_id] = $val->subject_desc;
        }
        return $data;
    }

    public function addSubject() {
        $data = array(
            'subject_code' => $this->input->post('code'),
            'subject_desc' => $this->input->post('desc'),
            'subject_unit' => $this->input->post('unit'),
            'admin_id' => $this->session->userdata('admin_id'),
        );
        $this->db->insert('subject', $data);
        log_message('debug', $this->db->last_query());
        return $this->db->affected_rows();
    }

    public function add() {

        $this->load->helper('form');
        $this->load->library('form_validation');

        $this->form_validation->set_rules(array(
            array(
                'field' => 'code',
                'label' => 'Subject Code',
                'rules' => 'required|min_length[3]|regex_match[' . SUBJECT_CODE_REGEX . ']',
            ),
            array(
                'field' => 'desc',
                'label' => 'Subject Description',
                'rules' => 'required|min_length[8]|alpha_numeric_spaces',
            ),
            array(
                'field' => 'unit',
                'label' => 'Unit',
                'rules' => 'required|numeric',
            ),
        ));
        $this->form_validation->set_error_delimiters('<span class="help-block"><i class="fa fa-warning"></i>', '</span>');

        if (!$this->form_validation->run()) {
            $this->load->model('Course_Model');


            $my_form = array(
                'caption' => 'Add Subject',
                'action' => '',
                'button_name' => 'save',
                'button_title' => 'Add Subject',
            );

            $my_inputs = array(
                'aa' =>
                array(
                    'size' => '12',
                    'attr' =>
                    array(
                        'code' => array(
                            'title' => 'Subject Code',
                            'type' => 'text',
                            'value' => NULL,
                        ),
                        'desc' => array(
                            'title' => 'Subject Description',
                            'type' => 'text',
                            'value' => NULL,
                        ),
                        'unit' => array(
                            'title' => 'Subject Unit',
                            'type' => 'text',
                            'value' => NULL,
                        ),
                    )
                ),
            );

            $this->load->view('form', array(
                'my_form' => $my_form,
                'my_forms_inputs' => $my_inputs,
            ));
        } else {
            $this->load->model('Subject_Model');
            $this->load->view('done', array(
                'msg' => ($this->Subject_Model->addSubject()) ? 'Subject, added successfully!' : 'Failed to add subject..'
            ));
        }
    }

    public function getAllSubjects() {
        $data = array();
        $subjects = $this->subjects();
        if ($subjects) {
            $inc = 1;
            foreach ($subjects as $value) {
                array_push($data, array(
                    'inc' => $inc++,
                    'code' => $value['code'],
                    'description' => $value['desc'],
                    'unit' => $value['unit'],
                    'option' => anchor(base_url('#'), 'update'),
                ));
            }
            return $data;
        }
        return FALSE;
    }

    public function subjects($subject_id = NULL) {
        $this->db->select('*');
        if (!is_null($subject_id)) {
            $this->db->where('subject_id', $subject_id);
        }
        $rs = $this->db->get('subject');
        if ($rs->row()) {
            $data = array();
            foreach ($rs->result() as $row) {
                array_push($data, array(
                    'id' => $row->subject_id,
                    'code' => $row->subject_code,
                    'desc' => $row->subject_desc,
                    'unit' => $row->subject_unit,
                    'timeadded' => $row->subject_added_time,
                    'admin_id' => $row->admin_id,
                ));
            }
            return $data;
        }
        return FALSE;
    }

}
