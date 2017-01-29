<?php

defined('BASEPATH') or exit('no direct script allowed');

class Assistant_Model extends MY_Model
{

        function __construct()
        {
                parent::__construct();
        }

        public function addAssistant()
        {
                $data = array(
                    'assistant_fullname' => $this->input->post('fullname'),
                    'assistant_email'    => $this->input->post('email'),
                    'admin_id'           => $this->session->userdata('admin_id'),
                );
                $this->db->insert('assistant', $data);
                log_message('debug', $this->db->last_query());
                return $this->db->affected_rows();
        }

        public function update($assistant)
        {

                $this->db->where('assistant_id', $assistant['id']);
                $this->db->update('assistant', array(
                    'assistant_email' => $assistant['email'],
                ));
                return $this->db->affected_rows();
        }

        public function form($assistant_id = NULL)
        {
                $this->load->helper('form');
                $this->load->library('form_validation');

                $this->form_validation->set_rules(array(
                    array(
                        'field' => 'email',
                        'label' => 'Email',
                        'rules' => 'required|valid_email|is_unique[assistant.assistant_email]|is_unique[teacher.teacher_email]',
                    ),
                ));
                $this->form_validation->set_error_delimiters('<span class="help-block"><i class="fa fa-warning"></i>', '</span>');
                if (!$this->form_validation->run())
                {
                        $assistant = NULL;
                        if (isset($assistant_id))
                        {
                                $assistant = $this->getSingleData($assistant_id, NULL);
                        }


                        $my_form = array(
                            'caption'      => ((isset($assistant)) ? 'Update' : 'Add') . ' Assistant',
                            'action'       => (isset($assistant)) ? 'assistants/update/' . $assistant['id'] : 'addassistant',
                            'button_name'  => 'save',
                            'button_title' => ((isset($assistant)) ? 'Update' : 'Add') . ' Assistant',
                        );

                        $my_inputs = array(
                            'aa' =>
                            array(
                                'size' => '12',
                                'attr' =>
                                array(
                                    'email' => array(
                                        'title' => 'Email',
                                        'type'  => 'text',
                                        'value' => ($assistant['email'] != NULL) ? $assistant['email'] : NULL,
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
                        if (isset($assistant_id))
                        {
                                $assistant = array(
                                    'email' => $this->input->post('email'),
                                    'id'    => $assistant_id,
                                );
                                $this->load->view('done', array(
                                    'msg' => ($this->Assistant_Model->update($assistant)) ? 'update successfully!' : 'Failed to update assistant..',
                                ));
                        }
                        else
                        {
                                $this->load->view('done', array(
                                    'msg' => ($this->Assistant_Model->addAssistant()) ? 'Added successfully!' : 'Failed to add assistant..',
                                ));
                        }
                }
        }

        public function getSingleData($assistant_id = NULL, $email = NULL)
        {
                $this->db->select('*');
                if (isset($assistant_id))
                {
                        $this->db->where('assistant_id', $assistant_id);
                }
                if (isset($email))
                {
                        $this->db->where('assistant_email', $email);
                }
                $rs = $this->db->get('assistant', 0, 1);
                if ($rs->num_rows())
                {
                        $row  = $rs->row();
                        return $data = array(
                            'id'       => $row->assistant_id,
                            'fullname' => $row->assistant_fullname,
                            'email'    => $row->assistant_email,
                            'status'   => $row->assistant_status,
                        );
                }
                return NULL;
        }

        public function getAllAssistant()
        {
                $response = array();
                $this->db->select('*');
                $query    = $this->db->get('assistant');
                if ($query->num_rows() > 0)
                {
                        $inc = 1;
                        foreach ($query->result() as $row)
                        {
                                $tmp["inc"]      = $inc++;
                                $tmp["fullname"] = $row->assistant_fullname;
                                $tmp["email"]    = $row->assistant_email;
                                $tmp["status"]   = ( $row->assistant_status) ? 'Enabled' : 'Disabled';
                                $tmp["option"]   = anchor(base_url() . 'assistants/update/' . $row->assistant_id, 'update');
                                array_push($response, $tmp);
                        }
                }
                return $response;
        }

        public function signInApplication()
        {
                $this->load->helper('date');
                $response = array();
                $email    = $this->input->post('email');
                $fullname = $this->input->post('fullname');

                $assistant = $this->getSingleData(NULL, $email);
                //email exist
                if ($this->db->where('assistant_email', $email)->count_all_results('assistant'))
                {
                        if ($assistant['status'])
                        {
                                $this->db->select('*');
                                $this->db->where('assistant_email', $email);
                                $this->db->update('assistant', array(
                                    'assistant_fullname' => $fullname,
                                ));
                                $response['error']        = FALSE;
                                $response['currentTime']  = my_current_datetime_information();
                                $response['assistant_id'] = $assistant['id'];
                                $response['message']      = ($this->db->affected_rows()) ? 'Succesfully Sign In.' : 'Fetching Assistant info done.';
                        }
                        else
                        {
                                $response['error']   = TRUE;
                                $response['message'] = 'User ' . $email . ' disabled';
                        }
                }
                else
                {
                        $response['error']   = TRUE;
                        $response['message'] = 'Assistant [' . $email . '] does not exist.';
                }
                $this->load->view('api', array(
                    'msg' => json_encode($response),
                ));
        }

}
