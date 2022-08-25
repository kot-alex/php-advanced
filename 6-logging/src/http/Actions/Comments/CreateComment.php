<?php

namespace Alex\Weblog\http\Actions\Comments;

use Alex\Weblog\http\Request;
use Alex\Weblog\http\Response;
use Alex\Weblog\Blog\Repositories\Interfaces\PostsRepositoryInterface;
use Alex\Weblog\http\Auth\JsonBodyUsernameIdentification;
use Alex\Weblog\Blog\Repositories\Interfaces\CommentsRepositoryInterface;
use Alex\Weblog\Blog\Entities\Comment;
use Alex\Weblog\Blog\Entities\UUID;
use Alex\Weblog\Exceptions\HttpException;
use Alex\Weblog\Exceptions\InvalidArgumentException;
use Alex\Weblog\Exceptions\PostNotFoundException;
use Alex\Weblog\http\Actions\ActionInterface;
use Alex\Weblog\http\ErrorResponse;
use Alex\Weblog\http\SuccessfulResponse;

class CreateComment implements ActionInterface
{
    public function __construct(
        private CommentsRepositoryInterface $commentsRepository,
        private PostsRepositoryInterface $postsRepository,
        private JsonBodyUsernameIdentification $identification,
    ) {
    }

    public function handle(Request $request): Response
    {
        try {
            $postUuid = new UUID($request->jsonBodyField('post_uuid'));
        } catch (HttpException | InvalidArgumentException $e) {
            return new ErrorResponse($e->getMessage());
        }

        try {
            $post = $this->postsRepository->get($postUuid);
        } catch (PostNotFoundException $e) {
            return new ErrorResponse($e->getMessage());
        }

        $author = $this->identification->user($request);

        $newCommentUuid = UUID::random();

        try {
            $comment = new Comment(
                $newCommentUuid,
                $post,
                $author,
                $request->jsonBodyField('text')
            );
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }

        $this->commentsRepository->save($comment);

        return new SuccessfulResponse([
            'uuid' => (string)$newCommentUuid,
        ]);
    }
}
