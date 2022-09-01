<?php

namespace Alex\Weblog\http\Actions\Posts;

use Alex\Weblog\http\Request;
use Alex\Weblog\http\Response;
use Alex\Weblog\Blog\Repositories\Interfaces\PostsRepositoryInterface;
use Alex\Weblog\http\Auth\TokenAuthenticationInterface;
use Alex\Weblog\Blog\Entities\Post;
use Alex\Weblog\Blog\Entities\UUID;
use Alex\Weblog\Exceptions\HttpException;
use Alex\Weblog\Exceptions\AuthException;
use Alex\Weblog\http\Actions\ActionInterface;
use Alex\Weblog\http\ErrorResponse;
use Alex\Weblog\http\SuccessfulResponse;

class CreatePost implements ActionInterface
{
    public function __construct(
        private PostsRepositoryInterface $postsRepository,
        private TokenAuthenticationInterface $authentication,
    ) {
    }

    public function handle(Request $request): Response
    {
        try {
            $author = $this->authentication->user($request);
        } catch (AuthException $e) {
            return new ErrorResponse($e->getMessage());
        }

        $newPostUuid = UUID::random();

        try {
            $post = new Post(
                $newPostUuid,
                $author,
                $request->jsonBodyField('title'),
                $request->jsonBodyField('text')
            );
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }

        $this->postsRepository->save($post);

        return new SuccessfulResponse([
            'uuid' => (string)$newPostUuid,
        ]);
    }
}
