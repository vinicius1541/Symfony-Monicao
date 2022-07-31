<?php

namespace App\Extension\Twig;

use App\Helper\DateHelper;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class IimExtension extends AbstractExtension
{
    public function getFilters():array
    {
        return [
            new TwigFilter('anoMesDiaExtenso', [$this, 'anoMesDiaExtensoFilter'])
        ];
    }
    /**
     * Formata o valor ano mes dia 20220731 para 31 Jul 2022 ou 31 Julho 2022
     * @param String $anoMesDia ex: 20220731
     * @param String $abbv 'complete' para mostrar 'Julho' ou 'abbv' para mostrar 'Jul'
     */
    public static function anoMesDiaExtensoFilter(string $anoMesDia, string $abbv): string
    {
        if( strstr($anoMesDia, '-') ){
            $anoMesDia = str_replace('-', '', $anoMesDia);
        }
        return DateHelper::formataAnoMesDiaExtenso($anoMesDia, $abbv);
    }
}