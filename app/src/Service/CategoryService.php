<?php
/**
 * Category service.
 */

namespace App\Service;

use App\Repository\CategoryRepository;
use App\Repository\RecipeRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use App\Entity\Category;

/**
 * Class CategoryService.
 */
class CategoryService implements CategoryServiceInterface
{
    /**
     * Category repository.
     * @var CategoryRepository $categoryRepository
     */
    private CategoryRepository $categoryRepository;

    /**
     * Paginator.
     * @var PaginatorInterface  $paginator
     */
    private PaginatorInterface $paginator;

    /**
     * Recipe repository
     * @var RecipeRepository $recipeRepository
     */
    private RecipeRepository $recipeRepository;

    /**
     * Recipe Service
     *
     * @var RecipeService $recipeService
     */
    private RecipeService $recipeService;

    /**
     * Constructor.
     *
     * @param CategoryRepository $categoryRepository
     * @param RecipeRepository   $recipeRepository
     * @param RecipeService      $recipeService
     * @param PaginatorInterface $paginator
     */
    public function __construct(CategoryRepository $categoryRepository, RecipeRepository $recipeRepository, RecipeService $recipeService, PaginatorInterface $paginator)
    {
        $this->categoryRepository = $categoryRepository;
        $this->recipeRepository = $recipeRepository;
        $this->recipeService = $recipeService;
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
            $this->categoryRepository->QueryAll(),
            $page,
            CategoryRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Save entity.
     *
     * @param Category $category Category entity
     */
    public function save(Category $category): void
    {
        $this->categoryRepository->save($category);
    }

    /**
     * Delete entity.
     *
     * @param Category $category Category entity
     */
    public function delete(Category $category): void
    {
        $recipes = $this->recipeRepository->findBy(
            ['category' => $category]
        );

        foreach ($recipes as $recipe) {
            $this->recipeService->delete($recipe);
        }

        $this->categoryRepository->delete($category);
    }
}
