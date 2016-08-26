<?php

namespace TimeTM\UserBundle\Handler;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Router;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

class LoginSuccessHandler implements AuthenticationSuccessHandlerInterface
{

    protected
        $router,
        $security;

    public function __construct(\Doctrine\ORM\EntityManager $em, Router $router, $security) {

        $this->em       = $em;
        $this->router   = $router;
        $this->security = $security;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {

        $default = $request->getSession()->get('ttm/agenda/current');

        $user = $this->security->getToken()->getUser();

        if (!$default) {
            $default = $this->em->getRepository('TimeTMUserBundle:User')->findDefaultAgenda($user);
            $request->getSession()->set('ttm/agenda/current', $default);
        }

        $response = new RedirectResponse($this->router->generate('dashboard'));

        return $response;
    }
}
