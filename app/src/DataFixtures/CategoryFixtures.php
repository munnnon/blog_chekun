<?php

/**
 * CategoryFixtures.
 */

namespace App\DataFixtures;

use App\Entity\Category;

/**
 * Class CategoryFixtures.
 */
class CategoryFixtures extends AbstractBaseFixtures
{
    /**
     * Load data.
     */
    public function loadData(): void
    {
        $this->createMany(10, 'categories', function (int $i) {
            $category = new Category();
            $category->setName($this->faker->word());

            return $category;
        });

        $this->manager->flush();
    }
}
