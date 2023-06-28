<?php
/**
 * recipe controller.
 */

namespace App\Controller;

use App\Entity\Recipe;
use App\Form\RateType;
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

    /**
     * Constructor.
     *
     * @param RecipeServiceInterface $recipeService Recipe service
     * @param TranslatorInterface  $translator  Translator
     */
    public function __construct(RecipeServiceInterface $recipeService, TranslatorInterface $translator)
    {
        $this->recipeService = $recipeService;
        $this->translator = $translator;
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
    public function index(Request $request): Response
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
    public function show(Request $request, CommentService $commentService, Recipe $recipe): Response
    {
        $score = $recipe->getScore();
        $votes = $recipe->getVotes();
        if($score && $votes) $rating = $score/$votes;
        $pagination = $commentService->getPaginatedListByRecipe($request->query->getInt('page', 1), $recipe);

        return $this->render(
            'recipe/show.html.twig',
            ['recipe' => $recipe, 'pagination' => $pagination, 'rating'=>$rating]
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
     * Edit action.
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
    #[Route('/rate/{id}', name: 'recipe_rate', requirements: ['id' => '[1-9]\d*'], methods: 'GET|POST')]
    public function rate (Request $request, Recipe $recipe): Response
    {
        $currentScore = $recipe->getScore();
        $form = $this->createForm(
            RateType::class,
            $recipe,
            [
                'method' => 'POST',
                'action' => $this->generateUrl('recipe_rate', ['id' => $recipe->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $rating = $form->get('score')->getData();
            $newScore = $currentScore + $rating;
            $currentVotes = $recipe->getVotes();
            $newVotes = $currentVotes + 1;
            $recipe->setVotes($newVotes);
            $recipe->setScore($newScore);

            $this->recipeService->save($recipe);

            $this->addFlash(
                'success',
                $this->translator->trans('message.rated_successfully')
            );

            return $this->redirectToRoute('recipe_index');
        }

        return $this->render(
            'recipe/rate.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }


}