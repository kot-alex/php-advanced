<?php

namespace Alex\Weblog\http\Auth;

use Alex\Weblog\Blog\Repositories\Interfaces\UsersRepositoryInterface;
use Alex\Weblog\Blog\Entities\User;
use Alex\Weblog\Blog\Entities\UUID;
use Alex\Weblog\Exceptions\AuthException;
use Alex\Weblog\Exceptions\HttpException;
use Alex\Weblog\Exceptions\InvalidArgumentException;
use Alex\Weblog\Exceptions\UserNotFoundException;
use Alex\Weblog\http\Request;

class JsonBodyUuidIdentification implements IdentificationInterface
{
    public function __construct(
        private UsersRepositoryInterface $usersRepository
    ) {
    }

    public function user(Request $request): User
    {
        try {
            $userUuid = new UUID($request->jsonBodyField('user_uuid'));
        } catch (HttpException | InvalidArgumentException $e) {
            throw new AuthException($e->getMessage());
        }

        try {
            return $this->usersRepository->get($userUuid);
        } catch (UserNotFoundException $e) {
            throw new AuthException($e->getMessage());
        }
    }
}
