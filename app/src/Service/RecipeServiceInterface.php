<?php
/**
 * Recipe service interface.
 */

namespace App\Service;

use App\Entity\Category;
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

    /**
     * get paginated list by category
     *
     * @param int      $page
     * @param Category $category
     *
     * @return PaginationInterface
     */
    public function getPaginatedListByCategory(int $page, Category $category): PaginationInterface;

    /**
     * get paginated lis by tag
     *
     * @param int $page
     * @param Tag $tag
     *
     * @return PaginationInterface
     */
    public function getPaginatedListByTag(int $page, Tag $tag): PaginationInterface;
}
