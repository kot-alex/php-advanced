<?php

namespace Alex\Weblog\http\Actions\Likes;

use Alex\Weblog\http\Request;
use Alex\Weblog\http\Response;
use Alex\Weblog\Exceptions\HttpException;
use Alex\Weblog\Exceptions\PostNotFoundException;
use Alex\Weblog\Exceptions\InvalidArgumentException;
use Alex\Weblog\Blog\Repositories\Interfaces\LikesRepositoryInterface;
use Alex\Weblog\http\Actions\ActionInterface;
use Alex\Weblog\http\ErrorResponse;
use Alex\Weblog\http\SuccessfulResponse;
use Alex\Weblog\Blog\UUID;

class FindByPostUuid implements ActionInterface
{
    public function __construct(
        private LikesRepositoryInterface $likesRepository
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
            $likes = $this->likesRepository->getByPostUuid(new UUID($uuid));
        } catch (PostNotFoundException | InvalidArgumentException $e) {
            return new ErrorResponse($e->getMessage());
        }

        return new SuccessfulResponse([
            'likes' => $likes
        ]);
    }
}
