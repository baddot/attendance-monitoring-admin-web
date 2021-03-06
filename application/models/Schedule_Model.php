<?php

defined('BASEPATH') or exit('no direct script allowed');

class Schedule_Model extends MY_Model
{


        const DB_TABLE = 'schedule';

        function __construct()
        {
                parent::__construct();
                $this->load->helper(array('day', 'date'));
        }

        public function approval()
        {
                $response      = array();
                $attendance_id = $this->input->post('attendance_id');
                $approval      = $this->input->post('approval');
                $this->db->where('attendance_id', $attendance_id);
                $this->db->update('attendance', array(
                    'attendance_approve' => ($approval == 'approve') ? TRUE : FALSE
                ));

// log_message('debug', $this->db->last_query());
                if ($this->db->affected_rows())
                {
                        $response['error']   = FALSE;
                        $response['message'] = 'done!';
                }
                else
                {
                        $response['error']   = TRUE;
                        $response['message'] = 'nothing change!';
                }
                $this->load->view('api', array(
                    'msg' => json_encode($response),
                ));
        }

        public function total($t_id, $M, $y)
        {
                $this->load->library('dtr');
                $this->db->select('*');
                $this->db->where('teacher_id', $t_id);
                $this->db->like('attendance_date', $y . ':' . $M);
                $rs = $this->db->get('attendance');

                $p     = 0;
                $a     = 0;
                $hours = 0;
                if ($rs)
                {
                        foreach ($rs->result() as $row)
                        {
                                if ($row->attendance_status == 'present')
                                {
                                        $p++;
                                        $hours += $this->total_hours_schedule($row->schedule_id);
                                }
                                else if ($row->attendance_status == 'absent')
                                {
                                        $a++;
                                }
                        }
                }
                $tmp['absent']  = $a;
                $tmp['present'] = $p;
                $tmp['hours']   = $hours;
                return $tmp;
        }

        private function total_hours_schedule($schedule_id)
        {
                $rs = $this->db->select('*')->where('schedule_id', $schedule_id)->get('schedule');

                if ($rs)
                {
                        $row = $rs->row();
                        return $this->dtr->total($row->schedule_start_time, $row->schedule_end_time);
                }
                return 0;
        }

        public function report_table($t_id, $M, $y)
        {



                $this->load->helper('day');

                $this->db->select('*');
                $this->db->where('teacher_id', $t_id);
                $this->db->like('attendance_date', $y . ':' . $M);
                $rs = $this->db->get('attendance');

                $days      = working_weekdays_in_month($M, $y);
                $days_have = array();
                $days_none = array();
                if ($rs)
                {
                        foreach ($rs->result() as $row)
                        {
// $days_present[] = ;

                                list($y, $m, $d) = explode(':', $row->attendance_date);

                                if (in_array($d, $days))
                                {
                                        $days_have[] = $d . '|' . $row->attendance_status;
                                }
                        }
                }
                $report = array();
                foreach ($days as $v)
                {
                        $found = FALSE;
                        foreach ($days_have as $v2)
                        {
                                list($d, $status) = explode('|', $v2);
                                if ($d == $v)
                                {
                                        $report[] = $v . '|' . $status;

                                        $found = TRUE;
                                        break;
                                }
                        }
                        if (!$found)
                        {
                                $report[] = $v . '|---';
                        }
                }

//        $this->load->view('view_report_result', array(
//            //  'data' => '[' . $this->db->last_query() . ']'
//            'data' => $days,
//            'data2' => $days_have,
//            'data3' => $report
//        ));


                $response = array();
                $inc      = 1;
                foreach ($report as $v)
                {
                        list($d, $status) = explode('|', $v);
                        $tmp["inc"]    = $inc++;
                        $tmp["date"]   = $d;
                        $tmp["status"] = $status;
                        array_push($response, $tmp);
                }

                return $response;
        }

        public function report_export($t_id, $M, $y)
        {

                $this->load->helper('day');

                $this->db->select('*');
                $this->db->where('teacher_id', $t_id);
                $this->db->like('attendance_date', $y . ':' . $M);
                $rs = $this->db->get('attendance');

                $days       = working_weekdays_in_month($M, $y);
// $days_have = array();
                $attendance = array();
                if ($rs)
                {
                        foreach ($rs->result() as $row)
                        {
// $days_present[] = ;

                                list($y, $m, $d) = explode(':', $row->attendance_date);

                                if (in_array($d, $days))
                                {
                                        $days_have[]  = $d . '|' . $row->attendance_status;
                                        $attendance[] = $row;
                                }
                        }
                }
// echo print_r($days_have);
// echo print_r($attendance);
                $report = array();
                foreach ($days as $v)
                {
                        $found = FALSE;
                        foreach ($attendance as $v2)
                        {
//  list($d, $status) = explode('|', $v2);
                                list($y, $m, $d) = explode(':', $v2->attendance_date);
                                if ($d == $v)
                                {
// $report[] = $v . '|' . $status;
                                        $report[] = $v2;
// $found = TRUE;
                                        break;
                                }
                        }
                }

//  echo print_r($report);
                $response = array();
                $inc      = 1;
//foreach ($report as $v) {
                foreach ($attendance as $v)
                {

                        $sched_row     = $this->db->select('*')->where('schedule_id', $v->schedule_id)->get('schedule')->row();
                        $subject_row   = $this->db->select('*')->where('subject_id', $sched_row->subject_id)->get('subject')->row();
                        $row_assistant = $this->db->select('*')->where('assistant_id', $v->assistant_id)->get('assistant')->row();

                        $tmp["inc"]           = $inc++;
                        $tmp["date"]          = format_date_to_string($v->attendance_date);
                        $tmp["day"]           = days($sched_row);
                        $tmp["status"]        = $v->attendance_status;
                        $tmp["subject"]       = $subject_row->subject_code . ' - ' . $subject_row->subject_desc;
                        $tmp["in"]            = $this->_24_to_12($sched_row->schedule_start_time);
                        $tmp["out"]           = $this->_24_to_12($sched_row->schedule_end_time);
                        $tmp["recorded_by"]   = $row_assistant->assistant_fullname;
                        $tmp["approve_by"]    = ($v->attendance_approve) ? 'Approved' : 'Not Approved';
                        $tmp["recorded_time"] = my_converter_datetime_format($v->attendance_date_timestap, 'time');
                        array_push($response, $tmp);
                }

                return $response;
        }

//24 to 12hr
        private function _24_to_12($t24)
        {
                $t = array(
                    '06:00:00' => '6:00 am',
                    '06:30:00' => '6:30 am',
                    '07:00:00' => '7:00 am',
                    '07:30:00' => '7:30 am',
                    '08:00:00' => '8:00 am',
                    '08:30:00' => '8:30 am',
                    '09:00:00' => '9:00 am',
                    '09:30:00' => '9:30 am',
                    '10:00:00' => '10:00 am',
                    '10:30:00' => '10:30 am',
                    '11:00:00' => '11:00 am',
                    '11:30:00' => '11:30 am',
                    '12:00:00' => '12:00 pm',
                    '12:30:00' => '12:30 pm',
                    '13:00:00' => '1:00 pm',
                    '13:30:00' => '1:30 pm',
                    '14:00:00' => '2:00 pm',
                    '14:30:00' => '2:30 pm',
                    '15:00:00' => '3:00 pm',
                    '15:30:00' => '3:30 pm',
                    '16:00:00' => '4:00 pm',
                    '16:30:00' => '4:30 pm',
                    '17:00:00' => '5:00 pm',
                    '17:30:00' => '5:30 pm',
                    '18:00:00' => '6:00 pm',
                    '18:30:00' => '6:30 pm',
                    '19:00:00' => '7:00 pm',
                    '19:30:00' => '7:30 pm'
                );
                return $t[$t24];
        }

        private function addSchedule()
        {
                $data = array(
                    'schedule_start_time'    => $this->input->post('starttime'),
                    'schedule_end_time'      => $this->input->post('endtime'),
                    'schedule_room'          => $this->input->post('room'),
                    'schedule_day_monday'    => ($this->input->post('monday') == 'monday') ? 1 : 0,
                    'schedule_day_tuesday'   => ($this->input->post('tuesday') == 'tuesday') ? 1 : 0,
                    'schedule_day_wednesday' => ($this->input->post('wednesday') == 'wednesday') ? 1 : 0,
                    'schedule_day_thursday'  => ($this->input->post('thursday') == 'thursday') ? 1 : 0,
                    'schedule_day_friday'    => ($this->input->post('friday') == 'friday') ? 1 : 0,
                    'schedule_day_saturday'  => ($this->input->post('saturday') == 'saturday') ? 1 : 0,
                    'schedule_day_sunday'    => ($this->input->post('sunday') == 'sunday') ? 1 : 0,
                    'teacher_id'             => $this->input->post('teacherid'),
                    'course_id'              => $this->input->post('courseid'),
                    'subject_id'             => $this->input->post('subjectid'),
                    'schedule_sy'            => $this->input->post('sy'),
                    'schedule_semester'      => $this->input->post('semester'),
                    'admin_id'               => $this->session->userdata('admin_id'),
                );
                $this->db->insert(self::DB_TABLE, $data);
                return ($this->db->affected_rows()) ? 'Added successfully!' : 'Failed to add schedule..';
        }

        public function form(/* $schedule_id = NULL */)
        {

//        $schedule_object = NULL;
//        if (!is_null($schedule_id)) {
//            $schedule_object_array = $this->get($schedule_id);
//            $schedule_object = $schedule_object_array[0];
//        }

                $this->load->helper('form');
                $this->load->library('form_validation');
                $this->form_validation->set_rules(array(
                    array(
                        'field' => 'starttime',
                        'label' => 'Start Time',
                        'rules' => 'required|min_length[3]|callback_check_time',
                    ),
                    array(
                        'field' => 'endtime',
                        'label' => 'End Time',
                        'rules' => 'required|min_length[3]', //|greater_than['.$start_chk.']',
                    ),
                    array(
                        'field' => 'room',
                        'label' => 'Room',
                        'rules' => 'required|min_length[3]',
                    ),
                    array(
                        'field' => 'teacherid',
                        'label' => 'Teacher',
                        'rules' => 'required|numeric',
                    ),
                    array(
                        'field' => 'courseid',
                        'label' => 'Course',
                        'rules' => 'required|numeric',
                    ),
                    array(
                        'field' => 'subjectid',
                        'label' => 'Subject',
                        'rules' => 'required|numeric',
                    ),
                    array(
                        'field' => 'sy',
                        'label' => 'Schedule School Year',
                        'rules' => 'required',
                    ),
                    array(
                        'field' => 'conflict',
                        'label' => 'Conflict',
                        'rules' => 'callback_check_conflict',
                    ),
                    array(
                        'field' => 'day',
                        'label' => 'Days',
                        'rules' => 'callback_check_day',
                    ),
                    array(
                        'field' => 'semester',
                        'label' => 'Semester',
                        'rules' => 'required|numeric',
                    ),
                ));
                $this->form_validation->set_error_delimiters('<span class="help-block"><i class="fa fa-warning"></i>', '</span>');
                if (!$this->form_validation->run())
                {
                        $this->load->model(array('Teacher_Model', 'Course_Model', 'Subject_Model'));

                        $this->load->helper(array('combobox', 'day'));
                        $my_form = array(
                            'caption'      => 'Add Schedule',
                            'action'       => '',
                            'button_name'  => 'save',
                            'button_title' => 'Add Schedule',
                        );

                        $my_inputs = array(
                            'aa' =>
                            array(
                                'size' => '6',
                                'attr' =>
                                array(
                                    'starttime' => array(
                                        'title'       => 'Start Time',
                                        'type'        => 'combo',
                                        // 'value' => $this->form_validation->set_value('starttime', (!is_null($schedule_object)) ? $schedule_object->schoolid : NULL),
                                        'value'       => NULL,
                                        'combo_value' => my_time_for_combo(),
                                    ),
                                    'endtime'   => array(
                                        'title'       => 'End Time',
                                        'type'        => 'combo',
                                        'value'       => NULL,
                                        'combo_value' => my_time_for_combo(),
                                    ),
                                    'room'      => array(
                                        'title' => 'Room',
                                        'type'  => 'text',
                                        'value' => NULL,
                                    ),
                                    'day'       => array(
                                        'title'          => 'Day',
                                        'type'           => 'checkbox',
                                        'value'          => NULL,
                                        'checkbox_value' => my_day_array(),
                                    ),
                                )
                            ),
                            'bb' =>
                            array(
                                'size' => '6',
                                'attr' =>
                                array(
                                    'teacherid' => array(
                                        'title'       => 'Teacher',
                                        'type'        => 'combo',
                                        'value'       => NULL,
                                        'combo_value' => $this->Teacher_Model->getTeachersForCombo(),
                                    ),
                                    'courseid'  => array(
                                        'title'       => 'Course',
                                        'type'        => 'combo',
                                        'value'       => NULL,
                                        'combo_value' => $this->Course_Model->getCourseForCombo(),
                                    ),
                                    'subjectid' => array(
                                        'title'       => 'Subject',
                                        'type'        => 'combo',
                                        'value'       => NULL,
                                        'combo_value' => $this->Subject_Model->getSubjectForCombo(),
                                    ),
                                    'sy'        => array(
                                        'title'       => 'Schedule School Year',
                                        'type'        => 'combo',
                                        'value'       => NULL,
                                        'combo_value' => my_schoolyear_for_combo(),
                                    ),
                                    'semester'  => array(
                                        'title'       => 'Semester',
                                        'type'        => 'combo',
                                        'value'       => NULL,
                                        'combo_value' => my_semester_for_combo(),
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
                        $this->load->view('done', array(
                            'msg' => $this->addSchedule(),
                        ));
                }
        }

        /**
         * return true if current semester
         * 
         * @param $row $row_schedule
         * @return boolean
         */
        private function check_current_semester($row_schedule)
        {
                $this->load->config('admin/config');
                list($current_month__, $current_date__, $curernt_year__) = explode(' ', my_current_datetime_information());
                log_message('debug', "\$current_month__: [$current_month__] "
                        . "\$current_date__: [$current_date__] \$curernt_year__: [$curernt_year__] ");

                list($year_start, $year_end) = explode('-', $row_schedule->schedule_sy);
                log_message('debug', "\$year_start: [$year_start] \$year_end: [$year_end] ");


                $mon_num = array_search($current_month__, my_month_array());
                log_message('debug', "\$mon_num: [$mon_num] ");

                if ($year_start == $curernt_year__)
                {
                        log_message('debug', "=========FIRST_SEMESTER============");
                        if ($this->config->item('first_semester_start') <= $mon_num ||
                                $this->config->item('first_semester_end') >= $mon_num)
                        {
                                log_message('debug', "=========FIRST_SEMESTER_IN============");
                                if ($row_schedule->schedule_semester == 1)
                                {
                                        log_message('debug', "=========FIRST_SEMESTER_IN_TRUE============");
                                        return TRUE;
                                }
                        }
                }
                else if ($year_end == $curernt_year__)
                {
                        log_message('debug', "=========SECOND_SEMESTER============");
                        if ($this->config->item('second_semester_start') <= $mon_num ||
                                ( $this->config->item('second_semester_end') >= $mon_num))
                        {
                                log_message('debug', "=========SECOND_SEMESTER_IN============");
                                if ($row_schedule->schedule_semester == 2)
                                {
                                        log_message('debug', "=========SECOND_SEMESTER_IN_TRUE============");
                                        return TRUE;
                                }
                        }
                        else if ($this->config->item('summer_semester_start') <= $mon_num &&
                                ( $this->config->item('summer_semester_end') >= $mon_num))
                        {
                                log_message('debug', "=========SUMMER_SEMESTER_IN============");
                                if ($row_schedule->schedule_semester == 3)
                                {
                                        log_message('debug', "=========SUMMER_SEMESTER_IN_TRUE============");
                                        return TRUE;
                                }
                        }
                }


                return FALSE;
        }

        public function getAllSchedule($ismobile, $ids = NULL, $teacher_email = NULL)
        {

                $response = array();
                $this->db->select('*');
                $this->db->join('teacher', 'schedule.teacher_id = teacher.teacher_id');
                $this->db->join('subject', 'schedule.subject_id = subject.subject_id');
                $this->db->join('course', 'schedule.course_id = course.course_id');
                if ($ismobile)
                {
//$rs_ = $this->db->query('SELECT DAYOFWEEK(CURDATE()) AS mytime');
//$row_ = $rs_->row();
//$int_day_oftheweek = $row_->mytime;
                        $dw  = '0';
//            if (ENVIRONMENT === 'development') {
//                //0 sunday ,1 monday
//                $dw = 0;
//            } else {
// $dw = date("w", date('y-m-d')) - 4;
                        $dw  = my_day();
//  }
                        $str = '';
                        switch ($dw)
                        {
                                case 'Sun':
                                        $str = 'sunday';
                                        break;
                                case 'Mon':
                                        $str = 'monday';
                                        break;
                                case 'Tue':
                                        $str = 'tuesday';
                                        break;
                                case 'Wed':
                                        $str = 'wednesday';
                                        break;
                                case 'Thu':
                                        $str = 'thursday';
                                        break;
                                case 'Fri':
                                        $str = 'friday';
                                        break;
                                case 'Sat':
                                        $str = 'saturday';
                                        break;
                        }
                        log_message('debug', 'new>>>' . $dw . '|' . $str . '|' . date('y-m-d'));
                        $this->db->where('schedule_day_' . $str, 1);
                }
                if (isset($ids))
                {
                        $x = explode(':', $ids);
                        foreach ($x as $t)
                        {
                                $this->db->or_where('schedule.schedule_id', $t);
                        }
                }
                if (isset($teacher_email))
                {
                        $this->db->where('teacher.teacher_email', trim($teacher_email));
                }
                $query = $this->db->get(self::DB_TABLE);

                if ($query->num_rows() > 0)
                {
                        $inc = 1;
                        foreach ($query->result() as $row)
                        {

                                $sem = '';
                                switch ($row->schedule_semester)
                                {
                                        case 1:
                                                $sem = '1st Semester';
                                                break;
                                        case 2:
                                                $sem = '2nd Semester';
                                                break;
                                        case 3:
                                                $sem = 'Summer Semester';
                                                break;
                                        default :
                                                break;
                                }
                                if ($ismobile)
                                {
                                        if ($teacher_email)
                                        {
                                                $tmp['attendance_id'] = $this->get_attendance_id($row->schedule_id);
                                        }
                                        if (!$this->check_current_semester($row))
                                                continue;
                                        $tmp['status']  = $this->status($row->schedule_id);
                                        $tmp['approve'] = $this->approve($row->schedule_id) ? 'Approved' : 'Not Approve';
                                } else
                                {
                                        $tmp['inc'] = $inc++;
                                }
                                $tmp['id']         = $row->schedule_id;
                                $tmp['room']       = $row->schedule_room;
                                $tmp['start_time'] = date('h:i a', strtotime($row->schedule_start_time));
                                $tmp['end_time']   = date('h:i a', strtotime($row->schedule_end_time));

                                $days = '';
                                $days .= (($row->schedule_day_monday) ? 'M' : '');
                                $days .= (($row->schedule_day_tuesday) ? 'T' : '');
                                $days .= (($row->schedule_day_wednesday) ? 'W' : '');
                                $days .= (($row->schedule_day_thursday) ? 'TH' : '');
                                $days .= (($row->schedule_day_friday) ? 'F' : '');
                                $days .= (($row->schedule_day_saturday) ? 'Sat' : '');
                                $days .= (($row->schedule_day_sunday) ? 'Sun' : '');

                                $tmp['days'] = $days;

                                $tmp['device']       = $row->teacher_device;
                                $tmp['teacher']      = $row->teacher_fullname;
                                $tmp['course']       = $row->course_desc;
                                $tmp['subject_desc'] = $row->subject_desc;
                                $tmp['subject_code'] = $row->subject_code;
                                $tmp['semester']     = $sem;
                                $tmp['year']         = $row->schedule_sy;
                                $tmp['unit']         = $row->subject_unit;





//    if (!$ismobile)
//      $tmp['option'] = anchor(base_url(), 'edit');
                                array_push($response, $tmp);
                        }
                }
                return $response;
        }

        private function get_attendance_id($schedule_id)
        {
//current date
//subject_id
                $rs = $this->db->select('attendance_id')
                        ->where('attendance_date', my_datetime_format())
                        ->where('schedule_id', $schedule_id)
                        ->get('attendance');

                if ($this->db->affected_rows() > 0)
                {
                        return $rs->row()->attendance_id;
                }

                return 0;
        }

        /**
         * present or abdsent to list in assistant activity APP 
         */
        private function status($schedule_id)
        {
                $status = '';
                $this->db->select('*');
                $this->db->where('schedule_id', $schedule_id);
                $this->db->where('attendance_date', my_datetime_format());
                $rs     = $this->db->get('attendance');
                if ($rs->row())
                {
                        $row    = $rs->row();
                        $status = $row->attendance_status;
                }
                else
                {
                        $status = 'no record.';
                }
                return $status;
        }

        /**
         * present or abdsent to list in assistant activity APP 
         */
        private function approve($schedule_id)
        {
                $approve = FALSE;
                $this->db->select('*');
                $this->db->where('schedule_id', $schedule_id);
                $this->db->where('attendance_date', my_datetime_format());
                $rs      = $this->db->get('attendance');
                if ($rs->row())
                {
                        $row     = $rs->row();
                        $approve = $row->attendance_approve;
                }
                else
                {
                        $status = 'no record.';
                }
                return $approve;
        }

        public function getAllSchedulesMobile($teacher_email)
        {
                $respone = array();
                if (1)
                {
                        $respone['error']    = FALSE;
                        $respone['message']  = 'Fetching schedule done.';
                        $respone['schedule'] = array();
                        $respone['schedule'] = $this->getAllSchedule(TRUE, NULL, $teacher_email);
                }
                else
                {
                        
                }
                return $respone;
        }

        public function sendingreport()
        {
                $respone      = array();
                $schedule_id  = $this->input->post('schedule_id');
                $status       = $this->input->post('status');
                $assistant_id = $this->input->post('assistant_id');
                if (!is_null($schedule_id) and ! is_null($status)and ! is_null($assistant_id))
                {
//select teacher_id

                        $this->db->select('*');
                        $this->db->where('schedule_id', $schedule_id);
                        $rs = $this->db->get(self::DB_TABLE);
                        log_message('debug', $this->db->last_query());
                        if (!$this->db->affected_rows())
                        {
                                $respone['error']   = TRUE;
                                $respone['message'] = 'teacher not exist';
                        }
                        $row  = $rs->row();
                        $data = array(
                            'schedule_id'              => $schedule_id,
                            'attendance_date'          => my_datetime_format(),
                            'attendance_date_timestap' => time(),
                            'teacher_id'               => $row->teacher_id,
                            'attendance_status'        => $status,
                            'assistant_id'             => $assistant_id,
                            'attendance_day'           => my_day(),
                        );
                        if ($row  = $this->check_if_alredy_recorded($data))
                        {
                                $this->load->model('Assistant_Model');
                                $assistant          = $this->Assistant_Model->getSingleData($row->assistant_id, NULL);
                                $respone['error']   = TRUE;
                                $respone['message'] = 'Already recorded by ' . $assistant['fullname'] . '.';
                        }
                        else
                        {
                                $this->db->insert('attendance', $data);
                                if ($this->db->affected_rows())
                                {
                                        $respone['error']   = FALSE;
                                        $respone['message'] = $schedule_id . ' | ' . $status;
                                }
                                else
                                {
                                        $respone['error']   = TRUE;
                                        $respone['message'] = 'failed to insert data';
                                }
                        }
                }
                return $respone;
        }

        private function check_if_alredy_recorded($data)
        {
                $this->db->select('assistant_id');
                $this->db->where('schedule_id', $data['schedule_id']);
                $this->db->where('attendance_date', $data['attendance_date']);
                $rs = $this->db->get('attendance');
// return $this->db->affected_rows();
                return $rs->row();
        }

        public function getAllAttendance()
        {
                $schedules  = $this->getAllSchedule(FALSE);
                $attendance = $this->attendance();
                if ($attendance)
                {
                        $this->load->helper('date');
                        $data = array();
                        $this->load->model('Assistant_Model');
                        $inc  = 1;
                        foreach ($attendance as $key => $value)
                        {
                                $single_schedule = NULL;
                                /* @var $schedules type */
                                foreach ($schedules as $sch)
                                {
                                        /* @var $sch type */
                                        if ($value['schedule_id'] === $sch['id'])
                                        {
                                                $single_schedule = $sch;
                                                break;
                                        }
                                }
                                $assistant = $this->Assistant_Model->getSingleData($value['assistant_id'], NULL);
                                array_push($data, array(
                                    'inc'           => $inc++,
                                    'date'          => my_converter_datetime_format($value['timestamp'], 'date'),
                                    'day'           => $value['day'],
                                    'status'        => $value['status'],
                                    'teacher'       => $single_schedule['teacher'],
                                    'subject'       => $single_schedule['subject_desc'],
                                    'in'            => $single_schedule['start_time'],
                                    'out'           => $single_schedule['end_time'],
                                    'assistant'     => $assistant ['fullname'],
                                    'approve'       => ( $value['approve']) ? 'Approved' : 'Not Approved',
                                    'time_recorded' => my_converter_datetime_format($value['timestamp'], 'time'),
                                ));
                        }
                        return $data;
                }
                return FALSE;
        }

        private function attendance($attedance_id = NULL)
        {
                $this->db->select('*');
                if (!is_null($attedance_id))
                {
                        $this->db->where('attendance_id', $attedance_id);
                }
                $rs = $this->db->get('attendance');
                if ($rs->row())
                {
                        $data = array();
                        foreach ($rs->result() as $row)
                        {
                                array_push($data, array(
                                    'id'           => $row->attendance_id,
                                    'date'         => $row->attendance_date,
                                    'timestamp'    => $row->attendance_date_timestap,
                                    'status'       => $row->attendance_status,
                                    'teacher_id'   => $row->teacher_id,
                                    'schedule_id'  => $row->schedule_id,
                                    'assistant_id' => $row->assistant_id,
                                    'day'          => $row->attendance_day,
                                    'approve'      => $row->attendance_approve,
                                ));
                        }
                        return $data;
                }
                return FALSE;
        }

}
