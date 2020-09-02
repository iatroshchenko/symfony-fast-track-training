<?php

namespace App\MessageHandler;

use App\Repository\CommentRepository;
use App\SpamChecker;
use App\Message\CommentMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class CommentMessageHandler implements MessageHandlerInterface
{
    private $spamChecker;
    private $entityManager;
    private $commentRepository;

    public function __construct(EntityManagerInterface $entityManager, SpamChecker $spamChecker, CommentRepository $commentRepository)
    {
        $this->spamChecker = $spamChecker;
        $this->entityManager = $entityManager;
        $this->commentRepository = $commentRepository;
    }

    public function __invoke(CommentMessage $message)
    {
        $comment = $this->commentRepository->find($message->getId());
        if (!$comment) return;

        $state = 'published';
        if ($this->spamChecker->getSpamScore($comment, $message->getContext()) > 0) $state = 'spam';

        $comment->setState($state);
        $this->entityManager->flush();
    }
}