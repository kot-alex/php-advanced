<?php

namespace Alex\Weblog\Blog\Commands\FakeData;

use Alex\Weblog\Blog\Repositories\Interfaces\UsersRepositoryInterface;
use Alex\Weblog\Blog\Repositories\Interfaces\PostsRepositoryInterface;
use Alex\Weblog\Blog\Repositories\Interfaces\CommentsRepositoryInterface;
use Alex\Weblog\Blog\Entities\User;
use Alex\Weblog\Blog\Entities\Post;
use Alex\Weblog\Blog\Entities\Comment;
use Alex\Weblog\Blog\Entities\UUID;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Faker\Generator;

class PopulateDB extends Command
{
    public function __construct(
        private Generator $faker,
        private UsersRepositoryInterface $usersRepository,
        private PostsRepositoryInterface $postsRepository,
        private CommentsRepositoryInterface $commentsRepository,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('fake-data:populate-db')
            ->setDescription('Populates DB with fake data')
            ->addOption(
                'users-number',
                'u',
                InputOption::VALUE_OPTIONAL,
                'Users number',
            )
            ->addOption(
                'posts-number',
                'p',
                InputOption::VALUE_OPTIONAL,
                'Posts number',
            );
    }

    protected function execute(
        InputInterface $input,
        OutputInterface $output,
    ): int {

        $usersNumber = $input->getOption('users-number');
        $postsNumber = $input->getOption('posts-number');

        empty($usersNumber) ? $usersNumber = 10 : $usersNumber;
        empty($postsNumber) ? $postsNumber = 20 : $postsNumber;

        $users = [];
        $posts = [];

        for ($i = 0; $i < $usersNumber; $i++) {
            $user = $this->createFakeUser();
            $users[] = $user;
            $output->writeln('User created: ' . $user->username());
        }

        foreach ($users as $user) {
            for ($i = 0; $i < $postsNumber; $i++) {
                $post = $this->createFakePost($user);
                $posts[] = $post;
                $output->writeln('Post created: ' . $post->title());
            }
        }

        foreach ($posts as $post) {
            for ($i = 0; $i < 1; $i++) {
                $comment = $this->createFakeComment($post, $post->user());
                $output->writeln('Post created: ' . $comment->uuid());
            }
        }

        return Command::SUCCESS;
    }

    private function createFakeUser(): User
    {
        $user = User::createFrom(
            $this->faker->username,
            $this->faker->password,
            $this->faker->firstName,
            $this->faker->lastName
        );

        $this->usersRepository->save($user);

        return $user;
    }

    private function createFakePost(User $author): Post
    {
        $post = new Post(
            UUID::random(),
            $author,
            $this->faker->sentence(6, true),
            $this->faker->realText
        );

        $this->postsRepository->save($post);

        return $post;
    }

    private function createFakeComment(Post $post, User $author): Comment
    {
        $comment = new Comment(
            UUID::random(),
            $post,
            $author,
            $this->faker->sentence(),
        );

        $this->commentsRepository->save($comment);

        return $comment;
    }
}
