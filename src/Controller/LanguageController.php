<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\LanguageManagerService;

class LanguageController extends AbstractController
{
    /**
     * @Route("/language/{lang}", name="language", defaults={"locale": "pt_BR"})
     */
    public function changeLanguage($lang, LanguageManagerService $languageManager): RedirectResponse
    {
        return $languageManager->setLanguage($lang);
    }
}