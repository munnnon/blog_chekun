<?php
/**
 * Post service.
 */

namespace App\Service;

use App\Entity\Post;
use App\Entity\User;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Security\Core\Security;

/**
 * Class PostService.
 */
class PostService implements PostServiceInterface
{
    /**
     * Items per page.
     *
     * Use constants to define configuration options that rarely change instead
     * of specifying them in app/config/config.yml.
     * See https://symfony.com/doc/current/best_practices.html#configuration
     *
     * @constant int
     */
    private const PAGINATOR_ITEMS_PER_PAGE = 10;

    /**
     * Constructor.
     *
     * @param PostRepository     $postRepository Post repository
     * @param UserRepository     $userRepository User repository
     * @param PaginatorInterface $paginator      Paginator
     * @param Security           $security       Security
     */
    public function __construct(private readonly PostRepository $postRepository, private readonly UserRepository $userRepository, private readonly PaginatorInterface $paginator, private readonly Security $security)
    {
    }

    /**
     * Get paginated list.
     *
     * @param int $page Page number
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getPaginatedList(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->postRepository->queryAll(),
            $page,
            self::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Save entity.
     *
     * @param Post $post Post entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function savePost(Post $post): void
    {
        $user = $this->security->getUser();
        if ($user instanceof User) {
            $post->setUser($user);
        }
        if (!$user) {
            throw new \RuntimeException('No logged-in user found.');
        }

        $this->postRepository->savePost($post);
    }

    /**
     * Delete entity.
     *
     * @param Post $post Post entity
     *
     * @throws ORMException
     */
    public function deletePost(Post $post): void
    {
        $this->postRepository->delete($post);
    }

    /**
     * Getter for UserRepository.
     *
     * @return UserRepository User Repository
     */
    public function getUserRepository(): UserRepository
    {
        return $this->userRepository;
    }
}
