<?php

defined('BASEPATH') or exit('mo direct script allowed');

class Attendance extends MY_Controller
{

        function __construct()
        {
                parent::__construct();
        }

        public function index()
        {
                $this->my_header_view();
                $this->my_table_view('Attendance Data', 'attendance', array(
                    'inc'           => '#',
                    'date'          => 'Date',
                    'day'           => 'Day',
                    'status'        => 'Status',
                    'teacher'       => 'Teacher',
                    'subject'       => 'Subject',
                    'in'            => 'In',
                    'out'           => 'Out',
                    'assistant'     => 'Recorded by',
                    'approve'       => 'Approved by Teacher',
                    'time_recorded' => 'Time Recorded',
                ));
                $this->load->view('bootstrap/footer');
        }

}
