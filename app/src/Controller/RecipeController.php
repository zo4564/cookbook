<?php
/**
 * recipe controller.
 */

namespace App\Controller;

use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use App\Service\CommentService;
use App\Service\RecipeServiceInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class recipeController.
 */
#[Route('/recipe')]
class RecipeController extends AbstractController
{
    /**
     * Recipe service.
     */
    private RecipeServiceInterface $recipeService;

    /**
     * Translator.
     */
    private TranslatorInterface $translator;
    private RecipeRepository $recipeRepository;

    /**
     * Constructor.
     *
     * @param RecipeServiceInterface $recipeService Recipe service
     * @param TranslatorInterface  $translator  Translator
     */
    public function __construct(RecipeServiceInterface $recipeService, TranslatorInterface $translator, RecipeRepository $recipeRepository)
    {
        $this->recipeService = $recipeService;
        $this->translator = $translator;
        $this->recipeRepository = $recipeRepository;
    }

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
        $pagination = $this->recipeService->getPaginatedList(
            $request->query->getInt('page', 1)
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
    public function show(Request $request, CommentService $commentService, RecipeRepository $recipeRepository, Recipe $recipe): Response
    {
        $pagination = $commentService->getPaginatedListByRecipe($request->query->getInt('page', 1), $recipe);

        return $this->render(
            'recipe/show.html.twig',
            ['recipe' => $recipe, 'pagination' => $pagination]
        );
    }

    /**
     * Create action.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     */
    #[Route('/create', name: 'recipe_create', methods: 'GET|POST', )]
    public function create(Request $request): Response
    {
        $recipe = new Recipe();
        $form = $this->createForm(
            RecipeType::class,
            $recipe,
            ['action' => $this->generateUrl('recipe_create')]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->recipeService->save($recipe);

            $this->addFlash(
                'success',
                $this->translator->trans('message.created_successfully')
            );

            return $this->redirectToRoute('recipe_index');
        }

        return $this->render('recipe/create.html.twig',  ['form' => $form->createView()]);
    }
    /**
     * Delete action.
     *
     * @param Request $request HTTP request
     * @param Recipe    $recipe    Recipe entity
     *
     * @return Response HTTP response
     */

    #[Route('/delete/{id}', name: 'recipe_delete', requirements: ['id' => '[1-9]\d*'], methods: 'GET|POST')]
    public function delete(Request $request, Recipe $recipe): Response
    {
        $form = $this->createForm(
            FormType::class,
            $recipe,
            [
                'method' => 'POST',
                'action' => $this->generateUrl('recipe_delete', ['id' => $recipe->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->recipeService->delete($recipe);

            $this->addFlash(
                'success',
                $this->translator->trans('message.deleted_successfully')
            );

            return $this->redirectToRoute('recipe_index');
        }

        return $this->render(
            'recipe/delete.html.twig',
            [
                'form' => $form->createView(),
                'recipe' => $recipe,
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     */
    #[Route('/edit/{id}', name: 'recipe_edit', requirements: ['id' => '[1-9]\d*'], methods: 'GET|POST')]
    public function edit (Request $request, Recipe $recipe): Response
    {
        $form = $this->createForm(
            RecipeType::class,
            $recipe,
            [
                'method' => 'POST',
                'action' => $this->generateUrl('recipe_edit', ['id' => $recipe->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->recipeService->save($recipe);

            $this->addFlash(
                'success',
                $this->translator->trans('message.deleted_successfully')
            );

            return $this->redirectToRoute('recipe_index');
        }

        return $this->render(
            'recipe/create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }


}