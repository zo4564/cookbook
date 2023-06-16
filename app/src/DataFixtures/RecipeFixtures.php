<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Recipe;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class RecipeFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{


    public function loadData(): void
    {
        if (null === $this->manager || null === $this->faker) {
            return;
        }

        $this->createMany(100, 'recipes', function (int $i) {
            $recipe = new Recipe();
            $recipe->setTitle($this->faker->word);
            $recipe->setContent($this->faker->sentence);

            /** @var Category $category */
            $category = $this->getRandomReference('categories');
            $recipe->setCategory($category);

            return $recipe;
        });

        $this->manager->flush();
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on.
     *
     * @return string[] of dependencies
     *
     * @psalm-return array{0: CategoryFixtures::class}
     */
    public function getDependencies(): array
    {
        return [CategoryFixtures::class];
    }
}