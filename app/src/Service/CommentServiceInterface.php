<?php
/**
 * Comment service interface.
 */

namespace App\Service;

use App\Entity\Comment;

/**
 * Interface CommentServiceInterface.
 */
interface CommentServiceInterface
{
    /**
     *  Save entity.
     *
     * @param Comment $comment Comment entity
     */
    public function saveComment(Comment $comment): void;

    /**
     * Delete entity.
     *
     * @param Comment $comment Comment entity
     */
    public function deleteComment(Comment $comment): void;
}
