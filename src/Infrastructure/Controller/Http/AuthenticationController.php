<?php

namespace ukickeru\AccessControlBundle\Infrastructure\Controller\Http;

use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\UsageTrackingTokenStorage;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use ukickeru\AccessControl\Model\Routes\ApplicationRoutesContainer;

class AuthenticationController extends AbstractController
{

    protected $container;
    
    private $authenticationUtils;

    private $security;

    private $pathToRedirectAfterLogin;

    private $pathToRedirectAfterLogout;

    public function __construct(
        ContainerInterface $container,
        AuthenticationUtils $authenticationUtils,
        Security $security,
        string $pathToRedirectAfterLogin,
        string $pathToRedirectAfterLogout
    ) {
        $this->container = $container;
        $this->authenticationUtils = $authenticationUtils;
        $this->security = $security;
        $this->pathToRedirectAfterLogin = $pathToRedirectAfterLogin;
        $this->pathToRedirectAfterLogout = $pathToRedirectAfterLogout;
    }

    /**
     * @Route(ApplicationRoutesContainer::LOGIN_ROUTE_PATH, name=ApplicationRoutesContainer::LOGIN_ROUTE_NAME)
     */
    public function login(): Response
    {

        if ($this->getUser()) {
            return $this->redirectToRoute($this->pathToRedirectAfterLogin);
        }

        $error = $this->authenticationUtils->getLastAuthenticationError();

        $lastUsername = $this->authenticationUtils->getLastUsername();

        return $this->render(
            '@access-control-bundle/Login/login.html.twig',
            [
                'last_username' => $lastUsername,
                'error' => $error
            ]
        );
    }

    /**
     * @Route(ApplicationRoutesContainer::LOGOUT_ROUTE_PATH, name=ApplicationRoutesContainer::LOGOUT_ROUTE_NAME)
     */
    public function logout()
    {
        /** @var UsageTrackingTokenStorage $tokenStorage */
        $tokenStorage = $this->container->get('security.token_storage');
        $tokenStorage->setToken(null);

        return $this->redirectToRoute($this->pathToRedirectAfterLogout);
    }
}
