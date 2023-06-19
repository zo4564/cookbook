<?php
/**
 * comment controller.
 */

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Recipe;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use App\Repository\RecipeRepository;
use App\Service\CommentServiceInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class commentController.
 */
#[Route('/comment')]
class CommentController extends AbstractController
{
    /**
     * Comment service.
     */
    private CommentServiceInterface $commentService;

    /**
     * Translator.
     */
    private TranslatorInterface $translator;

    /**
     * Constructor.
     *
     * @param CommentServiceInterface $recipeService Recipe service
     * @param TranslatorInterface  $translator  Translator
     */
    public function __construct(CommentServiceInterface $commentService, TranslatorInterface $translator, RecipeRepository $recipeRepository)
    {
        $this->commentService = $commentService;
        $this->translator = $translator;
    }
    /**
    /**
     * Index action.
     *
     * @param Request            $request        HTTP Request
     * @param CommentRepository     $commentRepository comment repository
     * @param PaginatorInterface $paginator      Paginator
     *
     * @return Response HTTP response
     */
    #[Route(name: 'comment_index', methods: 'GET')]
    public function index(Request $request, CommentRepository $commentRepository, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $commentRepository->findAll(),
            $request->query->getInt('page', 1),
            CommentRepository::PAGINATOR_ITEMS_PER_PAGE
        );

        return $this->render('comment/index.html.twig', ['pagination' => $pagination]);
    }

    /**
     * Show action.
     *
     * @param comment $comment comment entity
     *
     * @return Response HTTP response
     */
    #[Route(
        '/{id}',
        name: 'comment_show',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET',
    )]
    public function show(CommentRepository $commentRepository, int $id): Response
    {
        $comment = $commentRepository->find($id);
        return $this->render(
            'comment/show.html.twig',
            ['comment' => $comment]
        );
    }
    /**
     * Create action.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     */
    #[Route('/create/{id}', name: 'comment_create', requirements: ['id' => '[1-9]\d*'], methods: ['GET', 'POST'])]
    public function create(Request $request, int $id, RecipeRepository $recipeRepository): Response
    {
        $comment = new Comment();
        $user = $this->getUser();
        $recipe = $recipeRepository->find($id);

        if (!$recipe) {
            throw $this->createNotFoundException('Recipe not found');
        }

        $comment->setUser($user);
        $comment->setRecipe($recipe);

        $form = $this->createForm(
            CommentType::class,
            $comment,
            ['action' => $this->generateUrl('comment_create', ['id' => $id]), 'current_user' => $user, 'current_recipe' => $recipe]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->commentService->save($comment);

            $this->addFlash(
                'success',
                $this->translator->trans('message.created_successfully')
            );

            return $this->redirectToRoute('recipe_show', ['id' => $id]);
        }

        return $this->render('comment/create.html.twig', ['form' => $form->createView()]);
    }
}