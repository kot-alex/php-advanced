<?php

namespace Alex\Weblog\UnitTests\Actions;

use Alex\Weblog\Blog\Repositories\Interfaces\UsersRepositoryInterface;
use Alex\Weblog\Blog\Entities\User;
use Alex\Weblog\Blog\Entities\UUID;
use Alex\Weblog\Exceptions\UserNotFoundException;
use Alex\Weblog\http\Actions\Users\FindByUsername;
use Alex\Weblog\http\ErrorResponse;
use Alex\Weblog\http\Request;
use Alex\Weblog\http\SuccessfulResponse;
use PHPUnit\Framework\TestCase;

class FindByUsernameActionTest extends TestCase
{
    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testItReturnsErrorResponseIfNoUsernameProvided(): void
    {
        $request = new Request([], [], '');

        $usersRepository = $this->usersRepository([]);

        $action = new FindByUsername($usersRepository);

        $response = $action->handle($request);

        $this->assertInstanceOf(ErrorResponse::class, $response);

        $this->expectOutputString('{"success":false,"reason":"No such query param in the request: username"}');

        $response->send();
    }
    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testItReturnsErrorResponseIfUserNotFound(): void
    {
        $request = new Request(['username' => 'user1'], [], '');

        $usersRepository = $this->usersRepository([]);

        $action = new FindByUsername($usersRepository);

        $response = $action->handle($request);

        $this->assertInstanceOf(ErrorResponse::class, $response);

        $this->expectOutputString('{"success":false,"reason":"Not found"}');

        $response->send();
    }
    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testItReturnsSuccessfulResponse(): void

    {
        $request = new Request(['username' => 'user1'], [], '');

        $usersRepository = $this->usersRepository([
            new User(
                UUID::random(),
                'user1',
                'Ivan',
                'Ivanov'
            ),
        ]);

        $action = new FindByUsername($usersRepository);

        $response = $action->handle($request);

        $this->assertInstanceOf(SuccessfulResponse::class, $response);

        $this->expectOutputString('{"success":true,"data":{"username":"user1","first_name":"Ivan","last_name":"Ivanov"}}');

        $response->send();
    }

    private function usersRepository(array $users): UsersRepositoryInterface
    {
        return new class($users) implements UsersRepositoryInterface
        {
            public function __construct(
                private array $users
            ) {
            }

            public function save(User $user): void
            {
                // 
            }

            public function get(UUID $uuid): User
            {
                throw new UserNotFoundException("Not found");
            }

            public function getByUsername(string $username): User
            {
                foreach ($this->users as $user) {
                    if ($user instanceof User && $username === $user->username()) {
                        return $user;
                    }
                }
                throw new UserNotFoundException("Not found");
            }

            public function usernameExists(string $username): void
            {
                // 
            }

            public function deleteByUsername(string $username): void
            {
                // 
            }
        };
    }
}
