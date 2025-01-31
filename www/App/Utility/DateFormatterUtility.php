<?php

namespace PTW\Utility;

use DateTime;

class DateFormatterUtility
{
    static private $months = [
        1 => 'Gennaio', 2 => 'Febbraio', 3 => 'Marzo', 4 => 'Aprile', 5 => 'Maggio', 6 => 'Giugno',
        7 => 'Luglio', 8 => 'Agosto', 9 => 'Settembre', 10 => 'Ottobre', 11 => 'Novembre', 12 => 'Dicembre'
    ];

    static public function Format(string $date) : string {
        if ($date === null || $date === '')
            return \PTW\translation('image-no-date');
        $date = new DateTime($date);
        $month = self::$months[(int)$date->format('m')];

        return $date->format('d') . ' ' . $month . ' ' . $date->format('Y');
    }

}