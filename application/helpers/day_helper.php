<?php

defined('BASEPATH') or exit('no direct script allowed');

/**
 * 
 * @return String Sun,Mon
 */
function my_day() {
    $time = time();
    return mdate('%D', $time);
}

/**
 * 
 * @return array 'monday'=>'Monday'
 */
function my_day_array() {
    return array(
        'monday' => 'Monday',
        'tuesday' => 'Tuesday',
        'wednesday' => 'Wednesday',
        'thursday' => 'Thursday',
        'friday' => 'Friday',
        'saturday' => 'Saturday',
        'sunday' => 'Sunday'
    );
}

function days($row) {
    $d = array(
        'monday' => 'M',
        'tuesday' => 'T',
        'wednesday' => 'W',
        'thursday' => 'TH',
        'friday' => 'F',
        'saturday' => 'Sat',
        'sunday' => 'Sun',
    );
    $days = '';
    foreach ($d AS $k => $v) {
        $tmp = 'schedule_day_' . $k;
        if ($row->$tmp) {
            $days .= $v;
        }
    }
    if ($days == '') {
        $days = '--';
    }
    return $days;
}

/**
 * except weekdays in month and year
 * return array days in month
 * 
 * @param string $M December = 12
 * @param string $Y 2016
 * @return array
 */
function working_weekdays_in_month($M, $Y) {

    $workdays = array();
    $type = CAL_GREGORIAN;
    $month = date($M); // Month ID, 1 through to 12.
    $year = date($Y); // Year in 4 digit 2009 format.
    $day_count = cal_days_in_month($type, $month, $year); // Get the amount of days
//loop through all days
    for ($i = 1; $i <= $day_count; $i++) {

        $date = $year . '/' . $month . '/' . $i; //format date
        $get_name = date('l', strtotime($date)); //get week day
        $day_name = substr($get_name, 0, 3); // Trim day name to 3 chars
        //if not a weekend add day to array
        if ($day_name != 'Sun' && $day_name != 'Sat') {
            $workdays[] = $i;
        }
    }

// look at items in the array uncomment the next line
  //  print_r($workdays);
    return $workdays;
}
