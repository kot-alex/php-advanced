<?php

namespace Alex\Weblog\http\Actions\Users;

use Alex\Weblog\http\Request;
use Alex\Weblog\http\Response;
use Alex\Weblog\Blog\Repositories\Interfaces\UsersRepositoryInterface;
use Alex\Weblog\Blog\Entities\User;
use Alex\Weblog\http\Actions\ActionInterface;
use Alex\Weblog\Exceptions\HttpException;
use Alex\Weblog\Exceptions\UsernameAlreadyExistsException;
use Alex\Weblog\http\ErrorResponse;
use Alex\Weblog\http\SuccessfulResponse;

class CreateUser implements ActionInterface
{
    public function __construct(
        private UsersRepositoryInterface $usersRepository,
    ) {
    }

    public function handle(Request $request): Response
    {
        try {
            $username = $request->jsonBodyField('username');
            $this->usersRepository->usernameExists($username);
        } catch (HttpException | UsernameAlreadyExistsException $e) {
            return new ErrorResponse($e->getMessage());
        }

        try {
            $user = User::createFrom(
                $username,
                $request->jsonBodyField('password'),
                $request->jsonBodyField('first_name'),
                $request->jsonBodyField('last_name')

            );
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }

        $this->usersRepository->save($user);

        return new SuccessfulResponse([
            'uuid' => (string)$user->uuid(),
        ]);
    }
}
