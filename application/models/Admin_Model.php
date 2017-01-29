<?php

defined('BASEPATH') or exit('no direct script allowed');

class Admin_Model extends MY_Model
{

        function __construct()
        {
                parent::__construct();
        }

        public function validate()
        {
                $usr   = $this->input->post('username');
                $pwd   = $this->input->post('password');
                $this->db->select('*');
                $this->db->where('admin_username', $usr);
                //   $this->db->where('admin_status', 1);
                $query = $this->db->get('admin');
                if ($query->num_rows())
                {
                        $row = $query->row();
                        if (password_verify($pwd, $row->admin_password))
                        {
                                if ($row->admin_status)
                                {
                                        $this->session->set_userdata(array(
                                            'admin_id'       => $row->admin_id,
                                            'admin_fullname' => $row->admin_fullname,
                                            'validated'      => TRUE,
                                        ));
                                        return TRUE;
                                }
                                else
                                {
                                        return 'User Disabled.';
                                }
                        }
                        else
                        {
                                return 'Invalid username and/or password.';
                        }
                }
                else
                {
                        return 'Invalid username and/or password.';
                }
        }

}
