<?php
/**
 * recipe controller.
 */

namespace App\Controller;

use App\Entity\Recipe;
use App\Repository\RecipeRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class recipeController.
 */
#[Route('/recipe')]
class RecipeController extends AbstractController
{
    /**
     * Index action.
     *
     * @param Request            $request        HTTP Request
     * @param RecipeRepository     $recipeRepository recipe repository
     * @param PaginatorInterface $paginator      Paginator
     *
     * @return Response HTTP response
     */
    #[Route(name: 'recipe_index', methods: 'GET')]
    public function index(Request $request, RecipeRepository $recipeRepository, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $recipeRepository->findAll(),
            $request->query->getInt('page', 1),
            RecipeRepository::PAGINATOR_ITEMS_PER_PAGE
        );

        return $this->render('recipe/index.html.twig', ['pagination' => $pagination]);
    }

    /**
     * Show action.
     *
     * @param recipe $recipe recipe entity
     *
     * @return Response HTTP response
     */
    #[Route(
        '/{id}',
        name: 'recipe_show',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET',
    )]
    public function show(RecipeRepository $recipeRepository, int $id): Response
    {
        $recipe = $recipeRepository->find($id);
        return $this->render(
            'recipe/show.html.twig',
            ['recipe' => $recipe]
        );
    }
}