<?php

defined('BASEPATH') or exit('invalid');

class Api_web extends MY_Controller {

    function __construct() {
        parent::__construct();
    }

    public function assistants() {
        $this->load->model('Assistant_Model');
        $this->my_json_view($this->Assistant_Model->getAllAssistant());
    }

    public function schedules($ids = NULL) {
        $this->load->model('Schedule_Model');
        $this->my_json_view($this->Schedule_Model->getAllSchedule(FALSE, $ids, NULL));
    }

    public function teachers() {
        $this->load->model('Teacher_Model');
        $this->my_json_view($this->Teacher_Model->getAllTeacher());
    }

    public function attendance() {
        $this->load->model('Schedule_Model');
        $this->my_json_view($this->Schedule_Model->getAllAttendance());
    }

    public function subjects() {
        $this->load->model('Subject_Model');
        $this->my_json_view($this->Subject_Model->getAllSubjects());
    }

}

//        foreach (getallheaders() as $name => $value) {
//            echo "$name: $value<br />";
//        }
//        echo '<br />';
//        echo '<br />';
//        echo '<br />';
