<?php

namespace Alex\Weblog\http\Actions\Auth;

use Alex\Weblog\Blog\Entities\AuthToken;
use Alex\Weblog\Blog\Repositories\Interfaces\AuthTokensRepositoryInterface;
use Alex\Weblog\Exceptions\AuthException;
use Alex\Weblog\http\Actions\ActionInterface;
use Alex\Weblog\http\Auth\TokenAuthenticationInterface;
use Alex\Weblog\http\ErrorResponse;
use Alex\Weblog\http\Request;
use Alex\Weblog\http\Response;
use Alex\Weblog\http\SuccessfulResponse;
use DateTimeImmutable;

class LogOut implements ActionInterface
{
    public function __construct(
        private TokenAuthenticationInterface $tokenAuthentication,
        private AuthTokensRepositoryInterface $authTokensRepository
    ) {
    }

    public function handle(Request $request): Response
    {
        try {
            $user = $this->tokenAuthentication->user($request);
        } catch (AuthException $e) {
            return new ErrorResponse($e->getMessage());
        }

        $authToken = $this->authTokensRepository->getToken($user);

        $newAuthToken = new AuthToken(
            $authToken->token(),
            $user->uuid(),
            (new DateTimeImmutable())
        );

        $this->authTokensRepository->save($newAuthToken);

        return new SuccessfulResponse([
            'token' => $authToken->token(),
        ]);
    }
}
