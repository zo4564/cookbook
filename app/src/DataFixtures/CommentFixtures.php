<?php
/**
 * Comment fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Recipe;
use App\Entity\Comment;
use App\Entity\User;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

/**
 * Class CommentFixtures.
 */
class CommentFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Load data.
     *
     * @psalm-suppress PossiblyNullPropertyFetch
     * @psalm-suppress PossiblyNullReference
     * @psalm-suppress UnusedClosureParam
     */
    public function loadData(): void
    {
        if (null === $this->manager || null === $this->faker) {
            return;
        }

        $this->createMany(100, 'comments', function (int $i) {
            $comment = new Comment();
            $comment->setContent($this->faker->sentence);
            $comment->setCreatedAt(
                \DateTimeImmutable::createFromMutable(
                    $this->faker->dateTimeBetween('-100 days', '-1 days')
                )
            );

            /** @var Recipe $recipe */
            $recipe = $this->getRandomReference('recipes');
            $comment->setRecipe($recipe);

            /** @var User $user */
            $user = $this->getRandomReference('users');
            $comment->setUser($user);

            return $comment;
        });

        $this->manager->flush();
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on.
     *
     * @return string[] of dependencies
     *
     * @psalm-return array{0: RecipeFixtures::class}
     */
    public function getDependencies(): array
    {
        return [RecipeFixtures::class];
    }
}
