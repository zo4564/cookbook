<?php
/**
 * Comment service interface.
 */

namespace App\Service;

use App\Entity\recipe;
use App\Entity\Comment;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Interface CommentServiceInterface.
 */
interface CommentServiceInterface
{
    /**
     * Get paginated list.
     *
     * @param int $page Page number
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getPaginatedList(int $page): PaginationInterface;
    public function getPaginatedListByRecipe(int $page, Recipe $recipe): PaginationInterface;

}