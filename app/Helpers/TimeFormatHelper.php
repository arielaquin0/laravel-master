<?php

namespace App\Helpers;

class TimeFormatHelper {

    public static function sevenDays($days = 7){
        $data = [];
        $time = time();
        $i=0;
        while ($i<=$days) {
            $data[$i]['date'] = date("Y-m-d", $time - $i * 86400);
            $i++;
        }
        return array_reverse($data);
    }
}
