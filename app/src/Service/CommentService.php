<?php
/**
 * Comment service.
 */

namespace App\Service;

use App\Entity\Comment;
use App\Entity\Recipe;
use App\Repository\CommentRepository;
use App\Repository\RecipeRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class CommentService.
 */
class CommentService implements CommentServiceInterface
{
    /**
     * Comment repository.
     */
    private CommentRepository $CommentRepository;

    /**
     * Paginator.
     */
    private PaginatorInterface $paginator;

    /**
     * Constructor.
     *
     * @param CommentRepository     $CommentRepository Comment repository
     * @param PaginatorInterface $paginator      Paginator
     */
    public function __construct(CommentRepository $CommentRepository, PaginatorInterface $paginator)
    {
        $this->CommentRepository = $CommentRepository;
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
            $this->CommentRepository->QueryAll(),
            $page,
            CommentRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    public function getPaginatedListByRecipe(int $page, Recipe $recipe): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->CommentRepository->findBy(
                ['Recipe' => $recipe]
            ),
            $page,
            CommentRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }
    /**
     * Save entity.
     *
     * @param Comment $comment Recipe entity
     */
    public function save(Comment $comment): void
    {
        if ($comment->getId() == null)
        {
            $comment->setCreatedAt(new \DateTimeImmutable());
        }
        $this->CommentRepository->save($comment);
    }

    /**
     * Delete entity.
     *
     * @param Comment $comment Recipe entity
     */
    public function delete(Comment $comment): void
    {
        $this->CommentRepository->delete($comment);
    }
}
