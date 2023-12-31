<?php

/**
 * Recipe fixtures
 */
namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Recipe;
use App\Entity\Tag;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

/**
 * Recipe fixtures class
 */
class RecipeFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * load data
     *
     * @return void
     */
    public function loadData(): void
    {
        if (null === $this->manager || null === $this->faker) {
            return;
        }

        $this->createMany(100, 'recipes', function (int $i) {
            $recipe = new Recipe();
            $recipe->setTitle($this->faker->sentence);
            $content = $this->faker->paragraph;
            $recipe->setContent($content);
            $recipe->setCreatedAt(
                \DateTimeImmutable::createFromMutable(
                    $this->faker->dateTimeBetween('-100 days', '-1 days')
                )
            );
            $votes = $this->faker->numberBetween(100, 1000);
            $score = $votes * $this->faker->numberBetween(1, 5);
            $recipe->setScore($score);
            $recipe->setVotes($votes);
            $recipe->setRating($score / $votes);

            /** @var Category $category */
            $category = $this->getRandomReference('categories');
            $recipe->setCategory($category);

            /* @var Tag $tag */
            for ($j = 0; $j < 4; ++$j) {
                $tag = $this->getRandomReference('tags');
                $recipe->addTag($tag);
            }

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
