<?php

defined('BASEPATH') or exit('no direct sript allowed');

class Addschedule extends MY_Controller {

    function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->my_header_view();
        $this->load->model('Schedule_Model');
        $this->Schedule_Model->form();
        $this->load->view('bootstrap/footer');
    }

    public function check_day() {
        $this->form_validation->set_message('check_day', '{field} required.');
        $days = '';
        $days.=((($this->input->post('monday') == 'monday') ? 1 : 0) ? 'M' : '');
        $days.=((($this->input->post('tuesday') == 'tuesday') ? 1 : 0) ? 'T' : '');
        $days.=((($this->input->post('wednesday') == 'wednesday') ? 1 : 0) ? 'W' : '');
        $days.=((($this->input->post('thursday') == 'thursday') ? 1 : 0) ? 'TH' : '');
        $days.=((($this->input->post('friday') == 'friday') ? 1 : 0) ? 'F' : '');
        $days.=((($this->input->post('saturday') == 'saturday') ? 1 : 0) ? 'Sat' : '');
        $days.=((($this->input->post('sunday') == 'sunday') ? 1 : 0) ? 'Sun' : '');

        return strlen($days) > 0;
    }

    public function check_time() {
        $this->form_validation->set_message('check_time', '{field} must be lest the the other time.');
        $start_chk = strtotime($this->input->post('starttime'));
        $end_chk = strtotime($this->input->post('endtime'));
        if ($start_chk >= $end_chk) {
            return FALSE;
        }
        return TRUE;
    }

    public function check_conflict() {
        $this->form_validation->set_message('check_conflict', '<b>Conflict Schedule, see table above.</b>'); //{field}
        $rs = $this->db->select('schedule_id')
                //time
                ->where('schedule_start_time <= ', $this->input->post('starttime'))
                ->where('schedule_end_time >= ', $this->input->post('endtime'))
                ->where('subject_id', $this->input->post('subjectid'))
                ->where('schedule_sy', $this->input->post('sy'))
                ->get('schedule');
        $ids = '';
        if ($rs->num_rows() > 0) {
            foreach ($rs->result() as $row) {
                $ids.=$row->schedule_id . ':';
            }
        }
        $tmp = $rs->num_rows() > 0;
        if ($tmp) {
            $this->my_table_view('Conflict Schedules', 'schedules/' . $ids, array(
                'inc' => '#',
                'room' => 'Room',
                'start_time' => 'Start Time',
                'end_time' => 'End Time',
                'days' => 'Days',
                'teacher' => 'Teacher',
                'course' => 'Course',
                'subject_desc' => 'Subject',
                'subject_code' => 'Subject Code',
                'semester' => 'Semester',
                'year' => 'School Year',
                'unit' => 'Unit',
                    //  'option' => 'Option',
            ));
        }
        return !$tmp;
    }

}
