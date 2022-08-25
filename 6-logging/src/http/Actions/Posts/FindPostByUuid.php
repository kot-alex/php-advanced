<?php

namespace Alex\Weblog\http\Actions\Posts;

use Alex\Weblog\http\Request;
use Alex\Weblog\http\Response;
use Alex\Weblog\Exceptions\HttpException;
use Alex\Weblog\Blog\Repositories\Interfaces\PostsRepositoryInterface;
use Alex\Weblog\http\Actions\ActionInterface;
use Alex\Weblog\http\ErrorResponse;
use Alex\Weblog\Exceptions\PostNotFoundException;
use Alex\Weblog\Exceptions\InvalidArgumentException;
use Alex\Weblog\http\SuccessfulResponse;
use Alex\Weblog\Blog\Entities\UUID;

class FindPostByUuid implements ActionInterface
{
    public function __construct(
        private PostsRepositoryInterface $postsRepository
    ) {
    }

    public function handle(Request $request): Response
    {
        try {
            $postUuid = $request->query('uuid');
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }

        try {
            $post = $this->postsRepository->get(new UUID($postUuid));
        } catch (PostNotFoundException | InvalidArgumentException $e) {
            return new ErrorResponse($e->getMessage());
        }

        return new SuccessfulResponse([
            'username' => (string)$post->user()->username(),
            'title' => (string)$post->title(),
            'text' => (string)$post->text(),
        ]);
    }
}
