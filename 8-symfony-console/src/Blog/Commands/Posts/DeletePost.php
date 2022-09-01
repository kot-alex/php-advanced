<?php

namespace Alex\Weblog\Blog\Commands\Posts;

use Alex\Weblog\Blog\Repositories\Interfaces\PostsRepositoryInterface;
use Alex\Weblog\Blog\Entities\UUID;
use Alex\Weblog\Exceptions\PostNotFoundException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class DeletePost extends Command
{
    public function __construct(
        private PostsRepositoryInterface $postsRepository,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('posts:delete')
            ->setDescription('Deletes a post')
            ->addArgument(
                'uuid',
                InputArgument::REQUIRED,
                'UUID of a post to delete'
            )
            ->addOption(
                'check-existence',
                'c',
                InputOption::VALUE_NONE,
                'Check if post actually exists',
            );
    }

    protected function execute(
        InputInterface  $input,
        OutputInterface $output,
    ): int {
        $question = new ConfirmationQuestion(
            'Delete post [Y/n]? ',
            false
        );

        if (!$this->getHelper('question')
            ->ask($input, $output, $question)) {
            return Command::SUCCESS;
        }

        $uuid = new UUID($input->getArgument('uuid'));

        if ($input->getOption('check-existence')) {
            try {
                $this->postsRepository->get($uuid);
            } catch (PostNotFoundException $e) {
                $output->writeln($e->getMessage());
                return Command::FAILURE;
            }
        }

        $this->postsRepository->deleteByUuid($uuid);

        $output->writeln("Post $uuid deleted");

        return Command::SUCCESS;
    }
}
