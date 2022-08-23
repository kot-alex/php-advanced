<?php

namespace Alex\Weblog\http\Actions\Likes;

use Alex\Weblog\http\Request;
use Alex\Weblog\http\Response;
use Alex\Weblog\Blog\Repositories\Interfaces\PostsRepositoryInterface;
use Alex\Weblog\Blog\Repositories\Interfaces\UsersRepositoryInterface;
use Alex\Weblog\Blog\Repositories\Interfaces\LikesRepositoryInterface;
use Alex\Weblog\Blog\Like;
use Alex\Weblog\Blog\UUID;
use Alex\Weblog\Exceptions\HttpException;
use Alex\Weblog\Exceptions\InvalidArgumentException;
use Alex\Weblog\Exceptions\UserNotFoundException;
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
        private UsersRepositoryInterface $usersRepository,
    ) {
    }

    public function handle(Request $request): Response
    {
        try {
            $authorUuid = new UUID($request->jsonBodyField('author_uuid'));
            $postUuid = new UUID($request->jsonBodyField('post_uuid'));
        } catch (HttpException | InvalidArgumentException $e) {
            return new ErrorResponse($e->getMessage());
        }

        try {
            $author = $this->usersRepository->get($authorUuid);
        } catch (UserNotFoundException $e) {
            return new ErrorResponse($e->getMessage());
        }

        try {
            $post = $this->postsRepository->get($postUuid);
        } catch (PostNotFoundException $e) {
            return new ErrorResponse($e->getMessage());
        }

        try {
            $this->likesRepository->checkDuplicate($authorUuid, $postUuid);
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
