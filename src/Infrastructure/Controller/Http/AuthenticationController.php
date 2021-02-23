<?php

namespace ukickeru\AccessControlBundle\Infrastructure\Controller\Http;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class AuthenticationController extends AbstractController
{

    private $authenticationUtils;

    private $pathToRedirectAfterLogin;

    private $pathToRedirectAfterLogout;

    public function __construct(
        AuthenticationUtils $authenticationUtils,
        string $pathToRedirectAfterLogin,
        string $pathToRedirectAfterLogout
    ) {
        $this->authenticationUtils = $authenticationUtils;
        $this->pathToRedirectAfterLogin = $pathToRedirectAfterLogin;
        $this->pathToRedirectAfterLogout = $pathToRedirectAfterLogout;
    }

    /**
     * @Route("/login", name="login")
     */
    public function login(): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute($this->pathToRedirectAfterLogin);
        }

        $error = $this->authenticationUtils->getLastAuthenticationError();

        $lastUsername = $this->authenticationUtils->getLastUsername();

        return $this->render('@access-control-bundle/Login/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {
        return $this->redirectToRoute($this->pathToRedirectAfterLogout);
    }
}
