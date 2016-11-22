<?php

defined('BASEPATH') or exit('no direct script allowed');

class Course_Model extends MY_Model {

    function __construct() {
        parent::__construct();
    }

    public function addCourse() {
        $data = array(
            'course_desc' => $this->input->post('desc'),
            'admin_id' => $this->session->userdata('admin_id'),
        );
        $this->db->insert('course', $data);
        log_message('debug', $this->db->last_query());
        return $this->db->affected_rows();
    }

    public function getCourseForCombo() {
        $this->db->select('course_id,course_desc');
        $rs = $this->db->get('course');
        $data = array();
        foreach ($rs->result() as $val) {
            $data[$val->course_id] = $val->course_desc;
        }
        return $data;
    }

    public function add() {
        $this->load->helper('form');
        $this->load->library('form_validation');

        $this->form_validation->set_rules(array(
            array(
                'field' => 'desc',
                'label' => 'Description',
                'rules' => 'required|min_length[3]|alpha_numeric_spaces',
            ),
        ));
        $this->form_validation->set_error_delimiters('<span class="help-block"><i class="fa fa-warning"></i>', '</span>');
        if (!$this->form_validation->run()) {



            $my_form = array(
                'caption' => 'Add Course/Section',
                'action' => '',
                'button_name' => 'save',
                'button_title' => 'Add Course',
            );

            $my_inputs = array(
                'aa' =>
                array(
                    'size' => '12',
                    'attr' =>
                    array(
                        'desc' => array(
                            'title' => 'Description',
                            'type' => 'text',
                            'value' =>  NULL,
                        ),
                    )
                ),
            );

            $this->load->view('form', array(
                'my_form' => $my_form,
                'my_forms_inputs' => $my_inputs,
            ));
        } else {
            $this->load->model('Course_Model');
            $this->load->view('done', array(
                'msg' => ($this->Course_Model->addCourse()) ? 'Added successfully!' : 'Failed to add teacher..',
            ));
        }
    }

}
