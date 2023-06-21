<?php
/**
 * Recipe service.
 */

namespace App\Service;

use App\Entity\Recipe;
use App\Entity\Category;
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
    private RecipeRepository $RecipeRepository;

    /**
     * Paginator.
     */
    private PaginatorInterface $paginator;
    private CommentRepository $commentRepository;

    /**
     * Constructor.
     *
     * @param RecipeRepository     $RecipeRepository Recipe repository
     * @param PaginatorInterface $paginator      Paginator
     */
    public function __construct(RecipeRepository $RecipeRepository, PaginatorInterface $paginator, CommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
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
            $this->RecipeRepository->QueryAll(),
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
        if ($recipe->getId() == null)
        {
            $recipe->setCreatedAt(new \DateTimeImmutable());
        }
        $this->RecipeRepository->save($recipe);
    }

    /**
     * Delete entity.
     *
     * @param Recipe $recipe Recipe entity
     */
    public function delete(Recipe $recipe): void
    {
        $comments = $this->commentRepository->findBy(
            ['Recipe' => $recipe]
        );
        foreach ($comments as $comment) {
            $this->commentRepository->delete($comment);
        }
        $this->RecipeRepository->delete($recipe);
    }

}
