<?php

namespace Alex\Weblog\http\Auth;

use Alex\Weblog\Exceptions\HttpException;
use Alex\Weblog\Exceptions\AuthException;
use Alex\Weblog\Exceptions\UserNotFoundException;
use Alex\Weblog\Blog\Entities\User;
use Alex\Weblog\http\Request;
use Alex\Weblog\Blog\Repositories\Interfaces\UsersRepositoryInterface;

class PasswordAuthentication implements PasswordAuthenticationInterface
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
            $user = $this->usersRepository->getByUsername($username);
        } catch (UserNotFoundException $e) {
            throw new AuthException($e->getMessage());
        }

        try {
            $password = $request->jsonBodyField('password');
        } catch (HttpException $e) {
            throw new AuthException($e->getMessage());
        }

        if (!$user->checkPassword($password)) {
            throw new AuthException('Wrong password');
        }

        return $user;
    }
}
