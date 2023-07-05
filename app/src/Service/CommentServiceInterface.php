<?php
/**
 * Comment service interface.
 */

namespace App\Service;

use App\Entity\recipe;
use App\Entity\User;
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

    /**
     * get paginates list by recipe.
     *
     * @param int    $page
     * @param recipe $recipe
     *
     * @return PaginationInterface
     */
    public function getPaginatedListByRecipe(int $page, Recipe $recipe): PaginationInterface;

    /**
     * get paginated list by user
     *
     * @param int  $page
     * @param User $user
     *
     * @return PaginationInterface
     */
    public function getPaginatedListByUser(int $page, User $user): PaginationInterface;
}
