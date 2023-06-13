<?php
/**
 * Recipe service.
 */

namespace App\Service;

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
}
