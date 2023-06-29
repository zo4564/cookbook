<?php
/**
 * Tag service.
 */

namespace App\Service;

use App\Repository\TagRepository;
use App\Repository\RecipeRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use App\Entity\Tag;

/**
 * Class TagService.
 */
class TagService implements TagServiceInterface
{
    /**
     * Tag repository.
     */
    private TagRepository $tagRepository;

    /**
     * Paginator.
     */
    private PaginatorInterface $paginator;



    /**
     * Constructor.
     *
     * @param TagRepository     $tagRepository Tag repository
     * @param PaginatorInterface $paginator      Paginator
     */
    public function __construct(TagRepository $tagRepository, RecipeRepository $recipeRepository, RecipeService $recipeService, PaginatorInterface $paginator)
    {
        $this->tagRepository = $tagRepository;
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
            $this->tagRepository->QueryAll(),
            $page,
            TagRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }
    /**
     * Find by title.
     *
     * @param string $title Tag title
     *
     * @return Tag|null Tag entity
     */
    public function findOneByTitle(string $title): ?Tag
    {
        return $this->tagRepository->findOneByTitle($title);
    }


    /**
     * Save entity.
     *
     * @param Tag $tag Tag entity
     */
    public function save(Tag $tag): void
    {
        $title = $tag->getTitle();
        if(!$this->tagRepository->findBy(['title'=>$title]))
        {
            $this->tagRepository->save($tag);
        }

    }

    /**
     * Delete entity.
     *
     * @param Tag $tag Tag entity
     */
    public function delete(Tag $tag): void
    {
//        $recipes = $this->recipeRepository->findBy(
//            ['tag' => $tag]
//        );
//
//        foreach ($recipes as $recipe) {
//            $this->recipeService->delete($recipe);
//        }

        $this->tagRepository->delete($tag);
    }
}
