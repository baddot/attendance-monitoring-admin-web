<?php

defined('BASEPATH') or exit('no direct script allowed');

class Subject_Model extends MY_Model {

    const DB_TABLE = 'subject';

    function __construct() {
        parent::__construct();
    }

    public function getSubjectForCombo() {
        $this->db->select('subject_id,subject_desc');
        $rs = $this->db->get(self::DB_TABLE);
        $data = array();
        foreach ($rs->result() as $val) {
            $data[$val->subject_id] = $val->subject_desc;
        }
        return $data;
    }

    private function update($subject_id) {
        $data = array(
            'subject_code' => $this->input->post('code'),
            'subject_desc' => $this->input->post('desc'),
            'subject_unit' => $this->input->post('unit'),
                // 'admin_id' => $this->session->userdata('admin_id'),
        );
        $this->db->where('subject_id', $subject_id);
        $this->db->update(self::DB_TABLE, $data);
        return $this->db->affected_rows();
    }

    private function addSubject() {
        $data = array(
            'subject_code' => $this->input->post('code'),
            'subject_desc' => $this->input->post('desc'),
            'subject_unit' => $this->input->post('unit'),
            'admin_id' => $this->session->userdata('admin_id'),
        );
        $this->db->insert(self::DB_TABLE, $data);
        log_message('debug', $this->db->last_query());
        return $this->db->affected_rows();
    }

    public function form($subject_id = NULL) {
        $subject_object = NULL;
        if (!is_null($subject_id)) {
            $subject_object_array = $this->get($subject_id);
            $subject_object = $subject_object_array[0];
        }
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
                'caption' => (is_null($subject_object) ? 'Add Subject' : 'Update Subject'),
                'action' => '',
                'button_name' => 'save',
                'button_title' => (is_null($subject_object) ? 'Add Subject' : 'Update Subject'),
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
                            'value' => $this->form_validation->set_value('code', (!is_null($subject_object)) ? $subject_object->code : NULL),
                        ),
                        'desc' => array(
                            'title' => 'Subject Description',
                            'type' => 'text',
                            'value' => $this->form_validation->set_value('desc', (!is_null($subject_object)) ? $subject_object->desc : NULL),
                        ),
                        'unit' => array(
                            'title' => 'Subject Unit',
                            'type' => 'text',
                            'value' => $this->form_validation->set_value('unit', (!is_null($subject_object)) ? $subject_object->unit : NULL),
                        ),
                    )
                ),
            );

            $this->load->view('form', array(
                'my_form' => $my_form,
                'my_forms_inputs' => $my_inputs,
            ));
        } else {

            if (is_null($subject_object)) {
                $this->load->view('done', array(
                    'msg' => ($this->addSubject()) ? 'Subject, added successfully!' : 'Failed to add subject..'
                ));
            } else {
                $this->load->view('done', array(
                    'msg' => ($this->update($subject_object->id)) ? 'Subject, update successfully!' : 'Failed to update subject..'
                ));
            }
        }
    }

    public function getAllSubjects() {
        $data = array();
        $subjects_obj = $this->get();
        if ($subjects_obj) {
            $inc = 1;
            foreach ($subjects_obj as $value) {
                array_push($data, array(
                    'inc' => $inc++,
                    'code' => $value->code,
                    'description' => $value->desc,
                    'unit' => $value->unit,
                    'option' => anchor(base_url('subjects/update/' . $value->id), 'update'),
                ));
            }
            return $data;
        }
        return FALSE;
    }

    public function get($subject_id = NULL) {
        $this->db->select('*');
        if (!is_null($subject_id)) {
            $this->db->where('subject_id', $subject_id);
        }
        $rs = $this->db->get(self::DB_TABLE);
        if ($rs->row()) {
            $data = array();
            foreach ($rs->result() as $row) {
                $data[] = (object) array(
                            'id' => $row->subject_id,
                            'code' => $row->subject_code,
                            'desc' => $row->subject_desc,
                            'unit' => $row->subject_unit,
                            'timeadded' => $row->subject_added_time,
                            'admin_id' => $row->admin_id,
                );
            }
            return $data;
        }
        return FALSE;
    }

}
