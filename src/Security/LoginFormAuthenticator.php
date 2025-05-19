<?php

namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;

    }
    public function supports(Request $request) :bool
    {
        return $request->isMethod('POST') && $request->getPathInfo() == '/login';
        //symfony checks and if valid then throws to authenticate
    }

    public function getCredentials(Request $request)
    {

    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        // todo
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        // todo
    }



    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response
    {
        // todo - handle the failure response
        return new Response("authentication failed");
    }


    public function supportsRememberMe()
    {
        // todo
    }

    protected function getLoginUrl(Request $request): string
    {
        // TODO: Implement getLoginUrl() method.
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
                dd($credentials,$user);
            },$password)
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // TODO: Implement onAuthenticationSuccess() method.
    }
}
