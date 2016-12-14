<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Teacher_Model extends MY_Model {

    function __construct() {
        parent::__construct();
        $this->load->helper(array('date'));
    }

    public function addTeacher() {
        $data = array(
            'teacher_fullname' => $this->input->post('fullname'),
            'teacher_school_id' => $this->input->post('schoolid'),
            'teacher_email' => $this->input->post('email'),
            'admin_id' => $this->session->userdata('admin_id'),
        );
        $this->db->insert('teacher', $data);
        log_message('debug', $this->db->last_query());
        return $this->db->affected_rows();
    }

    public function getTeachersForCombo() {
        $this->db->select('teacher_id,teacher_fullname');
        $rs = $this->db->get('teacher');
        $data = array();
        foreach ($rs->result() as $val) {
            $data[$val->teacher_id] = $val->teacher_fullname;
        }
        return $data;
    }

    public function getAllTeacher() {
        $response = array();
        $this->db->select('*');
        $query = $this->db->get('teacher');
        if ($query->num_rows() > 0) {
            $inc = 1;
            foreach ($query->result() as $row) {
                $tmp["inc"] = $inc++;
                $tmp["fullname"] = $row->teacher_fullname;
                $tmp["id"] = $row->teacher_school_id;
                $tmp["email"] = $row->teacher_email;
                $tmp["option"] = anchor(base_url() . 'teachers/viewreport/' . $row->teacher_id, 'report') . ' | ' .
                        anchor(base_url() . 'teachers/update/' . $row->teacher_id, 'update') . ' | ' .
                        anchor(base_url() . 'teachers/scheduletoday/' . $row->teacher_id, 'today schedule');
                array_push($response, $tmp);
            }
        }
        return $response;
    }

    public function add() {
        $this->load->helper('form');
        $this->load->library('form_validation');
        //username
        $this->form_validation->set_rules(
                'email', 'Email', 'required|is_unique[assistant.assistant_email]|valid_email|is_unique[teacher.teacher_email]', array(
            'required' => 'You have not provided %s.',
            'is_unique' => 'This %s already exists.',
                )
        );
        //password
//        $this->form_validation->set_rules(
//                'password', 'Password', 'required|min_length[8]|regex_match[' . PASSWORD_REGEX . ']', array(
//            'required' => 'You have not provided %s.',
//            'regex_match' => PASSWORD_MSG_REGEX
//                )
//        );
        //school id
        $this->form_validation->set_rules(
                'schoolid', 'School ID', 'required|is_unique[teacher.teacher_school_id]|regex_match[' . SCHOOL_ID_REGEX . ']', array(
            'required' => 'You have not provided %s.',
            'is_unique' => 'This %s already exists.',
            'regex_match' => '%s must this format 1234-1234.'
                )
        );
        //fullname
        $this->form_validation->set_rules(
                'fullname', 'Fullname', 'required|min_length[8]|regex_match[' . FULLNAME_REGEX . ']', array(
            'required' => 'You have not provided %s.',
            'regex_match' => '%s contains only space,characters and period.'
                )
        );
        $this->form_validation->set_error_delimiters('<span class="help-block"><i class="fa fa-warning"></i>', '</span>');
        if (!$this->form_validation->run()) {

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
                        'fullname' => array(
                            'title' => 'Fullname',
                            'type' => 'text',
                            'value' => NULL,
                        ),
                        'schoolid' => array(
                            'title' => 'School ID',
                            'type' => 'text',
                            'value' => NULL,
                        ),
                        'email' => array(
                            'title' => 'Email',
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
            $this->load->model('Teacher_Model');
            $this->load->view('done', array(
                'msg' => ($this->Teacher_Model->addTeacher()) ? 'Added successfully!' : 'Failed to add teacher..',
            ));
        }
    }

    public function signInApplication() {
        $response = array();
        $email = $this->input->post('email');


        //email exist
        if ($this->db->where('teacher_email', $email)->count_all_results('teacher')) {
            $response['error'] = FALSE;
            $response['currentTime'] = my_current_datetime_information();
            $response['message'] = 'Fetching Teacher info done.';
        } else {
            $response['error'] = TRUE;
            $response['message'] = 'Teacher [' . $email . '] does not exist.';
        }
        $this->load->view('api', array(
            'msg' => json_encode($response),
        ));
    }

}
