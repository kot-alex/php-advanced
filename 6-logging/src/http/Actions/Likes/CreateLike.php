<?php

namespace Alex\Weblog\http\Actions\Likes;

use Alex\Weblog\http\Request;
use Alex\Weblog\http\Response;
use Alex\Weblog\Blog\Repositories\Interfaces\PostsRepositoryInterface;
use Alex\Weblog\http\Auth\JsonBodyUsernameIdentification;
use Alex\Weblog\Blog\Repositories\Interfaces\LikesRepositoryInterface;
use Alex\Weblog\Blog\Entities\Like;
use Alex\Weblog\Blog\Entities\UUID;
use Alex\Weblog\Exceptions\HttpException;
use Alex\Weblog\Exceptions\InvalidArgumentException;
use Alex\Weblog\Exceptions\PostNotFoundException;
use Alex\Weblog\Exceptions\LikeAlreadyExistsException;
use Alex\Weblog\http\Actions\ActionInterface;
use Alex\Weblog\http\ErrorResponse;
use Alex\Weblog\http\SuccessfulResponse;

class CreateLike implements ActionInterface
{
    public function __construct(
        private LikesRepositoryInterface $likesRepository,
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

        try {
            $this->likesRepository->checkDuplicate($author->uuid(), $postUuid);
        } catch (LikeAlreadyExistsException $e) {
            return new ErrorResponse($e->getMessage());
        }

        $newLikeUuid = UUID::random();

        try {
            $like = new Like(
                $newLikeUuid,
                $post,
                $author,
            );
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }

        $this->likesRepository->save($like);

        return new SuccessfulResponse([
            'uuid' => (string)$newLikeUuid,
        ]);
    }
}
