<?php

namespace Alex\Weblog\http\Actions\Auth;

use Alex\Weblog\Blog\Entities\AuthToken;
use Alex\Weblog\Blog\Repositories\Interfaces\AuthTokensRepositoryInterface;
use Alex\Weblog\Exceptions\AuthException;
use Alex\Weblog\http\Actions\ActionInterface;
use Alex\Weblog\http\Auth\PasswordAuthenticationInterface;
use Alex\Weblog\http\ErrorResponse;
use Alex\Weblog\http\Request;
use Alex\Weblog\http\Response;
use Alex\Weblog\http\SuccessfulResponse;
use DateTimeImmutable;

class LogIn implements ActionInterface
{
    public function __construct(
        private PasswordAuthenticationInterface $passwordAuthentication,
        private AuthTokensRepositoryInterface $authTokensRepository
    ) {
    }

    public function handle(Request $request): Response
    {
        try {
            $user = $this->passwordAuthentication->user($request);
        } catch (AuthException $e) {
            return new ErrorResponse($e->getMessage());
        }

        $authToken = new AuthToken(
            bin2hex(random_bytes(40)),
            $user->uuid(),
            (new DateTimeImmutable())->modify('+1 day')
        );

        $this->authTokensRepository->save($authToken);

        return new SuccessfulResponse([
            'token' => $authToken->token(),
        ]);
    }
}
