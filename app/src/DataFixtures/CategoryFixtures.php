<?php

/**
 * category fixtures
 */
namespace App\DataFixtures;

use App\Entity\Category;

/**
 * Category fixtures class
 */
class CategoryFixtures extends AbstractBaseFixtures
{
    /**
     * load data.
     */
    public function loadData(): void
    {
        $this->createMany(20, 'categories', function (int $i) {
            $category = new Category();
            $category->setName($this->faker->unique()->word);

            return $category;
        });

        $this->manager->flush();
    }
}
