<?php

defined('BASEPATH') or exit('Direct Script is not allowed');

class Dtr {

    public function total($s, $e) {
        $tmp = $this->to_sec($e) - $this->to_sec($s);

        return $this->to_hr($tmp);
    }

    private function to_sec($mytime) {
        list($h, $s) = explode(':', $mytime);
        $hrs = 0;
        if ($h >= 1) {
            $hrs = ($h * 60) * 60;
        }
        return( $hrs + $s);
    }

    private function to_hr($s) {
        return( $s / 60) / 60;
    }

}
