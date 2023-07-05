<?php

namespace App\DataFixtures;

/**
 * tag fixtures
 */
use App\Entity\Tag;

/**
 * tag fixtures class
 */
class TagFixtures extends AbstractBaseFixtures
{
    /**
     * load data.
     */
    public function loadData(): void
    {
        $this->createMany(50, 'tags', function (int $i) {
            $tag = new Tag();
            $tag->setTitle($this->faker->unique()->word);

            return $tag;
        });

        $this->manager->flush();
    }
}
