<?php
namespace App\Service;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class LanguageManagerService
{
    private SessionInterface $session;
    private RequestStack $requestStack;
    private UrlGeneratorInterface $router;

    public function __construct(SessionInterface $session, RequestStack $requestStack, UrlGeneratorInterface $router)
    {
        $this->session = $session;
        $this->requestStack = $requestStack;
        $this->router = $router;
    }

    public function setLanguage($lang): RedirectResponse
    {
        $this->session->set('_locale', $lang);
        $request = $this->requestStack->getCurrentRequest();
        $redirect = $request->server->get('HTTP_REFERER');
        if ( !$redirect ) {
            $redirect = $this->router->generate('dashboard');
        }

        return new RedirectResponse($redirect);
    }
}