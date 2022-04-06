<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\CommentRepository;

class CommentHelper
{
    // nouveau en PHP 8.1 : création d'un attribut par la variable déclarée
    public function __construct(private CommentRepository $repository)
    {   
    }

    public function countRecentCommentsForUser(User $user): int
    {

        return $this->repository->countForUser($user, new \DateTimeImmutable('-3 months'));
    }
}
