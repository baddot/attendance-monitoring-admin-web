<?php

defined('BASEPATH') or exit('no direct script allowed');

if (!function_exists('my_semester_for_combo'))
{

        /**
         * 
         * @return array 1-1st Semester,2-2nd Semester,3-Summer Semester
         */
        function my_semester_for_combo()
        {
                return array(
                    '1' => '1st Semester',
                    '2' => '2nd Semester',
                    '3' => 'Summer Semester',
                );
        }

}

if (!function_exists('my_schoolyear_for_combo'))
{

        /**
         * 
         * @return array 
         */
        function my_schoolyear_for_combo()
        {
                return array(
                    '2016-2017' => '2016-2017',
                    '2017-2018' => '2017-2018',
                    '2018-2019' => '2018-2019',
                );
        }

}

if (!function_exists('my_time_for_combo'))
{

        /**
         * 
         * @return array key = 24hrs, value = 12hrs
         */
        function my_time_for_combo()
        {
                return array(
                    '6:00'  => '6:00 am',
                    '6:30'  => '6:30 am',
                    '7:00'  => '7:00 am',
                    '7:30'  => '7:30 am',
                    '8:00'  => '8:00 am',
                    '8:30'  => '8:30 am',
                    '9:00'  => '9:00 am',
                    '9:30'  => '9:30 am',
                    '10:00' => '10:00 am',
                    '10:30' => '10:30 am',
                    '11:00' => '11:00 am',
                    '11:30' => '11:30 am',
                    '12:00' => '12:00 pm',
                    '12:30' => '12:30 pm',
                    '13:00' => '1:00 pm',
                    '13:30' => '1:30 pm',
                    '14:00' => '2:00 pm',
                    '14:30' => '2:30 pm',
                    '15:00' => '3:00 pm',
                    '15:30' => '3:30 pm',
                    '16:00' => '4:00 pm',
                    '16:30' => '4:30 pm',
                    '17:00' => '5:00 pm',
                    '17:30' => '5:30 pm',
                    '18:00' => '6:00 pm',
                    '18:30' => '6:30 pm',
                    '19:00' => '7:00 pm',
                    '19:30' => '7:30 pm'
                );
        }

}

if (!function_exists('my_course_combo'))
{

        function my_course_combo()
        {
                return array(
                    'college'    => 'College',
                    'highshool'  => 'High School',
                    'elememtary' => 'Elementary',
                );
        }

}
if (!function_exists('year_combo'))
{

        function year_combo($s, $e)
        {
                $data = array();
                for ($i = $s; $i <= $e; $i++)
                {
                        $data[$i] = $i;
                }
                return $data;
        }

}