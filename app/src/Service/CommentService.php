<?php
/**
 * Comment service.
 */

namespace App\Service;

use App\Entity\Comment;
use App\Repository\CommentRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;

/**
 * Class CommentService.
 */
class CommentService implements CommentServiceInterface
{
    /**
     * Constructor.
     *
     * @param CommentRepository $commentRepository Comment Repository
     */
    public function __construct(private readonly CommentRepository $commentRepository)
    {
    }

    /**
     * Save entity.
     *
     * @param Comment $comment Comment entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function saveComment(Comment $comment): void
    {
        $this->commentRepository->save($comment);
    }

    /**
     * Delete entity.
     *
     * @param Comment $comment Comment entity
     *
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function deleteComment(Comment $comment): void
    {
        $this->commentRepository->delete($comment);
    }
}
