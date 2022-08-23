<?php

namespace Alex\Weblog\http\Actions\Comments;

use Alex\Weblog\http\Request;
use Alex\Weblog\http\Response;
use Alex\Weblog\Exceptions\HttpException;
use Alex\Weblog\Blog\Repositories\Interfaces\CommentsRepositoryInterface;
use Alex\Weblog\http\Actions\ActionInterface;
use Alex\Weblog\http\ErrorResponse;
use Alex\Weblog\Exceptions\CommentNotFoundException;
use Alex\Weblog\Exceptions\InvalidArgumentException;
use Alex\Weblog\http\SuccessfulResponse;
use Alex\Weblog\Blog\UUID;

class FindCommentByUuid implements ActionInterface
{
    public function __construct(
        private CommentsRepositoryInterface $commentsRepository
    ) {
    }

    public function handle(Request $request): Response
    {
        try {
            $commentUuid = $request->query('uuid');
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }

        try {
            $comment = $this->commentsRepository->get(new UUID($commentUuid));
        } catch (CommentNotFoundException | InvalidArgumentException $e) {
            return new ErrorResponse($e->getMessage());
        }

        return new SuccessfulResponse([
            'uuid' => (string)$comment->uuid(),
            'author_uuid' => (string)$comment->author()->uuid(),
            'post_uuid' => (string)$comment->post()->uuid(),
        ]);
    }
}
