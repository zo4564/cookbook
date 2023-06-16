<?php
/**
 * Recipe service.
 */

namespace App\Service;

use App\Entity\Recipe;
use App\Entity\Category;
use App\Repository\RecipeRepository;
use App\Repository\CategoryRepository;
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
    private RecipeRepository $RecipeRepository;

    /**
     * Paginator.
     */
    private PaginatorInterface $paginator;

    /**
     * Constructor.
     *
     * @param RecipeRepository     $RecipeRepository Recipe repository
     * @param PaginatorInterface $paginator      Paginator
     */
    public function __construct(RecipeRepository $RecipeRepository, PaginatorInterface $paginator)
    {
        $this->RecipeRepository = $RecipeRepository;
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
            $this->RecipeRepository->findAll(),
            $page,
            RecipeRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    public function getPaginatedListByCategory(int $page, Category $category): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->RecipeRepository->findBy(
                ['category' => $category]
            ),
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
        $this->RecipeRepository->save($recipe);
    }

    /**
     * Delete entity.
     *
     * @param Recipe $recipe Recipe entity
     */
    public function delete(Recipe $recipe): void
    {
        $this->RecipeRepository->delete($recipe);
    }

}
