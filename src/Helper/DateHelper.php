<?php

namespace App\Helper;

class DateHelper
{
    private static $months = [
        '01' => ['complete' => 'Janeiro', 'abbv' => 'Jan'],
        '02' => ['complete' => 'Fevereiro', 'abbv' => 'Fev'],
        '03' => ['complete' => 'Março', 'abbv' => 'Mar'],
        '04' => ['complete' => 'Abril', 'abbv' => 'Abr'],
        '05' => ['complete' => 'Maio', 'abbv' => 'Mai'],
        '06' => ['complete' => 'Junho', 'abbv' => 'Jun'],
        '07' => ['complete' => 'Julho', 'abbv' => 'Jul'],
        '08' => ['complete' => 'Agosto', 'abbv' => 'Ago'],
        '09' => ['complete' => 'Setembro', 'abbv' => 'Set'],
        '10' => ['complete' => 'Outubro', 'abbv' => 'Out'],
        '11' => ['complete' => 'Novembro', 'abbv' => 'Nov'],
        '12' => ['complete' => 'Dezembro', 'abbv' => 'Dez']
    ];

    public static function month(string $month, string $abbv = 'complete')
    {
        return self::$months[$month][$abbv];
    }
    public static function formataAnoMesDiaExtenso(string $anoMesDia, string $abbv)
    {
        $day = substr($anoMesDia, 6, 2);
        $month = substr($anoMesDia, 4, 2);
        $year = substr($anoMesDia, 0, 4);

        if( key_exists($month, self::$months)) {
            $date = $day .' '. self::month($month, $abbv) .' '. $year;
        } else {
            $date = $anoMesDia;
        }
        return $date;
    }
}