<?php

defined('BASEPATH') or exit('no direct script allowed');

/**
 * 
 * @param type $month_number
 * @return string 1=January 2=February, etc..
 */
function my_month_array($month_number = 0) {
    $month = array(
        1 => 'January',
        2 => 'February',
        3 => 'March',
        4 => 'April',
        5 => 'May',
        6 => 'June',
        7 => 'July',
        8 => 'Augost',
        9 => 'September',
        10 => 'October',
        11 => 'November',
        12 => 'December',
    );
    if ($month_number == 0) {
        return $month;
    }

    return $month[$month_number];
}
