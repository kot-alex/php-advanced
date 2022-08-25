<?php

namespace Alex\Weblog\http\Auth;

use Alex\Weblog\Blog\Repositories\Interfaces\UsersRepositoryInterface;
use Alex\Weblog\Blog\Entities\User;
use Alex\Weblog\Exceptions\AuthException;
use Alex\Weblog\Exceptions\HttpException;
use Alex\Weblog\Exceptions\UserNotFoundException;
use Alex\Weblog\http\Request;

class JsonBodyUsernameIdentification implements IdentificationInterface
{
    public function __construct(
        private UsersRepositoryInterface $usersRepository
    ) {
    }

    public function user(Request $request): User
    {
        try {
            $username = $request->jsonBodyField('username');
        } catch (HttpException $e) {
            throw new AuthException($e->getMessage());
        }

        try {
            return $this->usersRepository->getByUsername($username);
        } catch (UserNotFoundException $e) {
            throw new AuthException($e->getMessage());
        }
    }
}
