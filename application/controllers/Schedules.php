<?php

defined('BASEPATH') or exit('mo direct script allowed');

class Schedules extends MY_Controller
{

        function __construct()
        {
                parent::__construct();
        }

        public function index()
        {
                $this->my_header_view();
                $this->my_table_view('Schedules Data', 'schedules', array(
                    'inc'          => '#',
                    'room'         => 'Room',
                    'start_time'   => 'Start Time',
                    'end_time'     => 'End Time',
                    'days'         => 'Days',
                    'teacher'      => 'Teacher',
                    'course'       => 'Course',
                    'subject_desc' => 'Subject',
                    'subject_code' => 'Subject Code',
                    'semester'     => 'Semester',
                    'year'         => 'School Year',
                    'unit'         => 'Unit',
                        //  'option'=>'Option',
                ));
                $this->load->view('bootstrap/footer');
        }

}
