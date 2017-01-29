<?php

defined('BASEPATH') or exit('nodirect script allowed');

class Download extends MY_Controller
{

        function __construct()
        {
                parent::__construct();
        }

        public function index()
        {
                $this->my_header_view();
                $this->load->helper('download');
                $pth = file_get_contents('file/app-release.apk');
                $nme = "AttendanceMonitoring.apk";
                force_download($nme, $pth);
        }

}
