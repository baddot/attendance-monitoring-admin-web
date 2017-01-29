<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Teacher_Model extends MY_Model
{


        const DB_TABLE = 'teacher';

        function __construct()
        {
                parent::__construct();
                $this->load->helper(array('date'));
        }

        public function addTeacher()
        {
                $data = array(
                    'teacher_fullname'  => $this->input->post('fullname'),
                    'teacher_school_id' => $this->input->post('schoolid'),
                    'teacher_email'     => $this->input->post('email'),
                    'teacher_device'    => $this->input->post('device'),
                    'admin_id'          => $this->session->userdata('admin_id'),
                );
                $this->db->insert(self::DB_TABLE, $data);
                log_message('debug', $this->db->last_query());
                return $this->db->affected_rows();
        }

        public function getTeachersForCombo()
        {
                $this->db->select('teacher_id,teacher_fullname');
                $rs   = $this->db->get(self::DB_TABLE);
                $data = array();
                foreach ($rs->result() as $val)
                {
                        $data[$val->teacher_id] = $val->teacher_fullname;
                }
                return $data;
        }

        public function getAllTeacher()
        {
                $response = array();
                $this->db->select('*');
                $query    = $this->db->get(self::DB_TABLE);
                if ($query->num_rows() > 0)
                {
                        $inc = 1;
                        foreach ($query->result() as $row)
                        {
                                $tmp["inc"]      = $inc++;
                                $tmp["fullname"] = $row->teacher_fullname;
                                $tmp["id"]       = $row->teacher_school_id;
                                $tmp["email"]    = $row->teacher_email;
                                $tmp["device"]   = $row->teacher_device;
                                $tmp["option"]   = anchor(base_url() . 'teachers/viewreport/' . $row->teacher_id, 'report') . ' | ' .
                                        anchor(base_url() . 'teachers/update/' . $row->teacher_id, 'update') . ' | ' .
                                        anchor(base_url() . 'teachers/scheduletoday/' . $row->teacher_id, 'today schedule');
                                array_push($response, $tmp);
                        }
                }
                return $response;
        }

        private function update($teacher_id)
        {
                $data = array(
                    'teacher_fullname'  => $this->input->post('fullname'),
                    'teacher_school_id' => $this->input->post('schoolid'),
                    'teacher_email'     => $this->input->post('email'),
                        // 'admin_id' => $this->session->userdata('admin_id'),
                );
                $this->db->where('teacher_id', $teacher_id);
                $this->db->update(self::DB_TABLE, $data);
                return $this->db->affected_rows();
        }

        private function get($teacher_id = NULL)
        {
                $this->db->select('*');
                if (!is_null($teacher_id))
                {
                        $this->db->where('teacher_id', $teacher_id);
                }
                $rs = $this->db->get(self::DB_TABLE);

                if ($rs->num_rows() > 0)
                {
                        $data_array = array();
                        foreach ($rs->result() as $k => $v)
                        {
                                $data_array[] = (object) array(
                                            'id'       => $v->teacher_id,
                                            'fullname' => $v->teacher_fullname,
                                            'email'    => $v->teacher_email,
                                            'schoolid' => $v->teacher_school_id,
                                            'device'   => $v->teacher_device
                                );
                        }
                        return $data_array;
                }
                else
                {
                        show_error('Teacher not found.');
                }
        }

        public function form($teacher_id = NULL)
        {
                $teacher_object = NULL;
                if (!is_null($teacher_id))
                {
                        $teacher_object_array = $this->get($teacher_id);
                        $teacher_object       = $teacher_object_array[0];
                }

                $this->load->helper('form');
                $this->load->library('form_validation');
                //username
                $this->form_validation->set_rules(
                        'email', 'Email', 'required|is_unique[assistant.assistant_email]|valid_email|is_unique[teacher.teacher_email]', array(
                    'required'  => 'You have not provided %s.',
                    'is_unique' => 'This %s already exists.',
                        )
                );
                //device
                $this->form_validation->set_rules(
                        'device', 'Deice', 'required', array(
                    'required' => 'You have not provided %s.',
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
                    'required'    => 'You have not provided %s.',
                    'is_unique'   => 'This %s already exists.',
                    'regex_match' => '%s must this format 1234-1234.'
                        )
                );
                //fullname
                $this->form_validation->set_rules(
                        'fullname', 'Fullname', 'required|min_length[8]|regex_match[' . FULLNAME_REGEX . ']', array(
                    'required'    => 'You have not provided %s.',
                    'regex_match' => '%s contains only space,characters and period.'
                        )
                );
                $this->form_validation->set_error_delimiters('<span class="help-block"><i class="fa fa-warning"></i>', '</span>');
                if (!$this->form_validation->run())
                {

                        $my_form = array(
                            'caption'      => (is_null($teacher_object) ? 'Add Teacher' : 'Update Teacher'),
                            'action'       => current_url(),
                            'button_name'  => 'save',
                            'button_title' => (is_null($teacher_object) ? 'Add Teacher' : 'Update Teacher'), // ,
                        );

                        $my_inputs = array(
                            'aa' =>
                            array(
                                'size' => '12',
                                'attr' =>
                                array(
                                    'fullname' => array(
                                        'title' => 'Fullname',
                                        'type'  => 'text',
                                        'value' => $this->form_validation->set_value('fullname', (!is_null($teacher_object)) ? $teacher_object->fullname : NULL),
                                    ),
                                    'schoolid' => array(
                                        'title' => 'School ID',
                                        'type'  => 'text',
                                        'value' => $this->form_validation->set_value('schoolid', (!is_null($teacher_object)) ? $teacher_object->schoolid : NULL),
                                    ),
                                    'email'    => array(
                                        'title' => 'Email',
                                        'type'  => 'text',
                                        'value' => $this->form_validation->set_value('email', (!is_null($teacher_object)) ? $teacher_object->email : NULL),
                                    ),
                                    'device'   => array(
                                        'title' => 'Device',
                                        'type'  => 'text',
                                        'value' => $this->form_validation->set_value('device', (!is_null($teacher_object)) ? $teacher_object->device : NULL),
                                    ),
                                )
                            ),
                        );

                        $this->load->view('form', array(
                            'my_form'         => $my_form,
                            'my_forms_inputs' => $my_inputs,
                        ));
                }
                else
                {

                        if (is_null($teacher_object))
                        {
                                $this->load->view('done', array(
                                    'msg' => ($this->addTeacher()) ? 'Added successfully!' : 'Failed to add teacher..',
                                ));
                        }
                        else
                        {
                                $this->load->view('done', array(
                                    'msg' => ($this->update($teacher_object->id)) ? 'Update successfully!' : 'Failed to Update teacher..',
                                ));
                        }
                }
        }

        public function signInApplication()
        {
                $response = array();
                $email    = $this->input->post('email');


                //email exist
                if ($this->db->where('teacher_email', $email)->count_all_results(self::DB_TABLE))
                {
                        $response['error']       = FALSE;
                        $response['currentTime'] = my_current_datetime_information();
                        $response['message']     = 'Fetching Teacher info done.';
                }
                else
                {
                        $response['error']   = TRUE;
                        $response['message'] = 'Teacher [' . $email . '] does not exist.';
                }
                $this->load->view('api', array(
                    'msg' => json_encode($response),
                ));
        }

}
