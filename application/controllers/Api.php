<?php

defined('BASEPATH') or exit('no direct script allowed');

class Api extends CI_Controller {

    function __construct() {
        parent::__construct();
    }

    public function approval() {
        $this->load->model('Schedule_Model');
        $this->load->view('api', array(
            'msg' => json_encode($this->Schedule_Model->approval()),
        ));
    }

    public function signinassistant() {
        $this->load->model('Assistant_Model');
        $this->Assistant_Model->signInApplication();
    }

    public function signinteacher() {
        $this->load->model('Teacher_Model');
        $this->Teacher_Model->signInApplication();
    }

    public function schedules() {
        $this->load->model('Schedule_Model');
        $this->load->view('api', array(
            'msg' => json_encode($this->Schedule_Model->getAllSchedulesMobile($this->input->post('email'))),
        ));
    }

    public function sendingreport() {
        $this->load->model('Schedule_Model');
        $this->load->view('api', array(
            'msg' => json_encode($this->Schedule_Model->sendingreport()),
        ));
    }

}
