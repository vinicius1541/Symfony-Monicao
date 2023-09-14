<?php

namespace App\Extension\Twig;

use App\Helper\DateHelper;
use Symfony\Component\Translation\Translator;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class IimExtension extends AbstractExtension
{
    private static TranslatorInterface $translator;
    public function __construct(
        TranslatorInterface $trans
    )
    {
        self::$translator = $trans;
    }

    public function getFilters():array
    {
        return [
            new TwigFilter('anoMesDiaExtenso', [$this, 'anoMesDiaExtensoFilter'])
        ];
    }
    public function getFunctions():array
    {
        return array(
            new TwigFunction('translate', array($this, 'translateFunction'))
        );
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
    /**
     * Translate a message
     * @param string $word Can be 'Symfony is Great' or using Keywords 'symfony.is.great'
     * @param string $locale 'en', 'pt_BR', 'fr_FR', etc...
     * @return string
     */
    public static function translateFunction(string $word, string $locale):string
    {
        return self::$translator->trans($word, [],'messages', $locale);
    }
}