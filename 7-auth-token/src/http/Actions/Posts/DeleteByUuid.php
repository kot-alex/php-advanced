<?php

namespace Alex\Weblog\http\Actions\Posts;

use Alex\Weblog\http\Request;
use Alex\Weblog\http\Response;
use Alex\Weblog\Exceptions\HttpException;
use Alex\Weblog\Blog\Repositories\Interfaces\PostsRepositoryInterface;
use Alex\Weblog\http\Actions\ActionInterface;
use Alex\Weblog\http\ErrorResponse;
use Alex\Weblog\Exceptions\InvalidArgumentException;
use Alex\Weblog\Exceptions\PostNotFoundException;
use Alex\Weblog\http\SuccessfulResponse;
use Alex\Weblog\Blog\Entities\UUID;

class DeleteByUuid implements ActionInterface
{
    public function __construct(
        private PostsRepositoryInterface $postsRepository
    ) {
    }

    public function handle(Request $request): Response
    {
        try {
            $uuid = $request->query('uuid');
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }

        try {
            $this->postsRepository->get(new UUID($uuid));
        } catch (PostNotFoundException | InvalidArgumentException $e) {
            return new ErrorResponse($e->getMessage());
        }

        $this->postsRepository->deleteByUuid(new UUID($uuid));

        return new SuccessfulResponse([
            'uuid' => (string)$uuid,
        ]);
    }
}
