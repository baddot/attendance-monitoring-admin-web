<?php

defined('BASEPATH') or exit('not allowed.');

class MY_Controller extends CI_Controller
{

        function __construct()
        {
                parent::__construct();
                $this->check_session();
        }

        private function check_session()
        {
                if (!$this->session->userdata('validated'))
                {
                        redirect('login');
                }
        }

        public function my_json_view($array)
        {
                $this->load->view('api', array(
                    'msg' => json_encode($array),
                ));
        }

        public function my_header_view()
        {
                $this->load->view('bootstrap/header', array(
                    'navigations' => $this->my_navigations()
                ));
        }

        /**
         * to load view page
         * @param string $caption caption of table
         * @param string $controller where to get data
         * @param array $columns key-tdata key, value = title header of table columns
         */
        public function my_table_view($caption, $controller, $columns)
        {
                $this->load->view('table', array(
                    'caption'    => $caption,
                    'columns'    => $columns,
                    'controller' => $controller,
                ));
        }

        public function my_navigations()
        {
                return array(
                    'dashboard'  =>
                    array(
                        'label' => 'Dashboard',
                        'desc'  => 'Dashboard Description',
                        'icon'  => 'dashboard-dial',
                    ),
                    'attendance' =>
                    array(
                        'label' => 'Attendance',
                        'desc'  => 'Attendance Description',
                        'icon'  => 'hourglass',
                    ),
                    'teachers'   =>
                    array(
                        'label' => 'Teachers',
                        'desc'  => 'Teachers Description',
                        'icon'  => 'app-window-with-content',
                    ),
                    'schedules'  =>
                    array(
                        'label' => 'Schedules',
                        'desc'  => 'schedules Description',
                        'icon'  => 'calendar',
                    ),
                    'subjects'   =>
                    array(
                        'label' => 'Subjects',
                        'desc'  => 'Subjects Description',
                        'icon'  => 'table',
                    ),
                    'assistants' =>
                    array(
                        'label' => 'Assistants',
                        'desc'  => 'Assistants Description',
                        'icon'  => 'eye',
                    ),
                    //sub menu
                    'add'        =>
                    array(
                        'label' => 'Add Data',
                        'icon'  => 'chevron-down',
                        'sub'   =>
                        array(
                            'addteacher'   =>
                            array(
                                'label' => 'Add Teacher',
                                'desc'  => 'Add Teacher Description',
                                'icon'  => 'pencil',
                            ),
                            'addassistant' =>
                            array(
                                'label' => 'Add Assistant',
                                'desc'  => 'Add Assistant Description',
                                'icon'  => 'pencil',
                            ),
                            'addschedule'  =>
                            array(
                                'label' => 'Add Schedule',
                                'desc'  => 'Add Schedule Description',
                                'icon'  => 'pencil',
                            ),
                            'addsubject'   =>
                            array(
                                'label' => 'Add Subject',
                                'desc'  => 'Add Subject Description',
                                'icon'  => 'pencil',
                            ),
                            'addcourse'    =>
                            array(
                                'label' => 'Add Course/Section',
                                'desc'  => 'Add Course Description',
                                'icon'  => 'pencil',
                            ),
                        ),
                    ),
                    'download'   =>
                    array(
                        'label' => 'Download APK ver 2.2.1',
                        'desc'  => 'Download Description',
                        'icon'  => 'download',
                    ),
                );
        }

}
