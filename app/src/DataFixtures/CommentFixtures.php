<?php

/**
 * CommentFixtures.
 */

namespace App\DataFixtures;

use App\Entity\Comment;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

/**
 * Class CommentFixtures.
 */
class CommentFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Load data.
     */
    public function loadData(): void
    {
        $this->createMany(50, 'comments', function () {
            $comment = new Comment();
            $comment->setContent($this->faker->text());
            $comment->setCreatedAt(
                \DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween('-100 days', '-1 days'))
            );

            $post = $this->getRandomReference('posts');
            $comment->setPost($post);

            $user = $this->getRandomReference('users');
            $comment->setUser($user);

            return $comment;
        });

        $this->manager->flush();
    }

    /**
     * Getter for dependencies.
     *
     * @return string[]
     */
    public function getDependencies(): array
    {
        return [
            PostFixtures::class,
        ];
    }
}
