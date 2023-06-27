<?php
/**
 * Recipe service interface.
 */

namespace App\Service;

use App\Entity\Category;
use App\Entity\Recipe;
use App\Entity\Tag;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Interface RecipeServiceInterface.
 */
interface RecipeServiceInterface
{
    /**
     * Get paginated list.
     *
     * @param int $page Page number
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getPaginatedList(int $page): PaginationInterface;
    public function getPaginatedListByCategory(int $page, Category $category): PaginationInterface;
    public function getPaginatedListByTag(int $page, Tag $tag): PaginationInterface;
}