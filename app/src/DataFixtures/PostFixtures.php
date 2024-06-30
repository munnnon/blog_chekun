<?php
/**
 * Post Fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Post;
use App\Entity\User;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

/**
 * Class Post Fixtures.
 */
class PostFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Load data.
     */
    public function loadData(): void
    {
        $this->createMany(20, 'posts', function () {
            $post = new Post();
            $post->setTitle($this->faker->sentence);

            $content = $this->faker->paragraph();
            $content = substr($content, 0, 255);
            $post->setContent($content);

            $post->setCreatedAt(
                \DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween('-100 days', '-1 days'))
            );
            $post->setUpdatedAt(
                \DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween('-100 days', '-1 days'))
            );
            /**
             * @var Category $category
             */
            $category = $this->getRandomReference('categories');
            $post->setCategory($category);

            /** @var User $author */
            $author = $this->getRandomReference('users');
            $post->setUser($author);

            return $post;
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
            UserFixtures::class,
            CategoryFixtures::class,
        ];
    }
}
