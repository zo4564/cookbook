<?php
/**
 * Recipe service.
 */

namespace App\Service;

use App\Entity\Recipe;
use App\Entity\Category;
use App\Entity\Tag;
use App\Repository\CommentRepository;
use App\Repository\RecipeRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class RecipeService.
 */
class RecipeService implements RecipeServiceInterface
{
    /**
     * Recipe repository.
     */
    private RecipeRepository $recipeRepository;

    /**
     * Paginator.
     */
    private PaginatorInterface $paginator;
    private CommentRepository $commentRepository;

    /**
     * Constructor.
     *
     * @param RecipeRepository   $recipeRepository
     * @param PaginatorInterface $paginator
     * @param CommentRepository  $commentRepository
     */
    public function __construct(RecipeRepository $recipeRepository, PaginatorInterface $paginator, CommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
        $this->recipeRepository = $recipeRepository;
        $this->paginator = $paginator;
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
            $this->recipeRepository->QueryAll(),
            $page,
            RecipeRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * get paginated list by category
     *
     * @param int      $page
     * @param Category $category
     *
     * @return PaginationInterface
     */
    public function getPaginatedListByCategory(int $page, Category $category): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->recipeRepository->findBy(
                ['category' => $category]
            ),
            $page,
            RecipeRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * get paginated lis by tag
     *
     * @param int $page
     * @param Tag $tag
     *
     * @return PaginationInterface
     */
    public function getPaginatedListByTag(int $page, Tag $tag): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->recipeRepository->findRecipesByTag($tag),
            $page,
            RecipeRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Save entity.
     *
     * @param Recipe $recipe Recipe entity
     */
    public function save(Recipe $recipe): void
    {
        if (null === $recipe->getId()) {
            $recipe->setCreatedAt(new \DateTimeImmutable());
        }
        $score = $recipe->getScore();
        $votes = $recipe->getVotes();
        if ($score && $votes) {
            $recipe->setRating($score / $votes);
        }
        $this->recipeRepository->save($recipe);
    }

    /**
     * Delete entity.
     *
     * @param Recipe $recipe Recipe entity
     */
    public function delete(Recipe $recipe): void
    {
        $comments = $this->commentRepository->findBy(
            ['recipe' => $recipe]
        );
        foreach ($comments as $comment) {
            $this->commentRepository->delete($comment);
        }
        $this->recipeRepository->delete($recipe);
    }
}
