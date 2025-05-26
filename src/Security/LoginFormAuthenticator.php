<?php

namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\CustomCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;

class LoginFormAuthenticator extends AbstractLoginFormAuthenticator
{
    private UserRepository $userRepository;
    private RouterInterface $router;

    public function __construct(UserRepository $userRepository, RouterInterface $router)
    {
        $this->userRepository = $userRepository;
        $this->router = $router;
    }

    public function supports(Request $request) :bool
    {
        return $request->isMethod('POST') && $request->getPathInfo() == '/login';
    }

    public function getCredentials(Request $request)
    {
        // Not needed in new Symfony versions (Deprecated)
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        // Not needed in new Symfony versions (Deprecated)
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        // Not needed in new Symfony versions (Deprecated)
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response
    {

        $request->getSession()->set(Security::class,$exception);

        return new RedirectResponse($this->router->generate('app_login'));
    }

    public function supportsRememberMe()
    {
        // Optional - you can configure remember me logic here
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->router->generate('app_login');
    }

    public function authenticate(Request $request)
    {
        $email = $request->request->get("email");
        $password = $request->request->get("password");

        return new Passport(
            new UserBadge($email, function ($userIdentifier) {
                $user = $this->userRepository->findOneBy(['email' => $userIdentifier]);
                return $user;
            }),
            new CustomCredentials(function ($credentials, User $user) {
                // Validate password (you may replace this with the password hasher)
                return $credentials === 'Password';
            }, $password)
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): Response
    {
        return new RedirectResponse($this->router->generate('app_homepage'));
    }
}
