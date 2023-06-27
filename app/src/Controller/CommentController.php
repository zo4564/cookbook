<?php
declare(strict_types=1);
/**
 * comment controller.
 */

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Recipe;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use App\Repository\RecipeRepository;
use App\Service\CommentServiceInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
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
    public function create(Request $request, Recipe $recipe): Response
    {
        $comment = new Comment();
        $user = $this->getUser();
        $comment->setUser($user);
        $comment->setRecipe($recipe);

        $form = $this->createForm(
            CommentType::class,
            $comment,
            ['action' => $this->generateUrl('comment_create', ['id' => $recipe->getId()]), 'current_user' => $user, 'current_recipe' => $recipe]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->commentService->save($comment);

            $this->addFlash(
                'success',
                $this->translator->trans('message.created_successfully')
            );

            return $this->redirectToRoute('recipe_show', ['id' => $recipe->getId()]);
        }

        return $this->render('comment/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * Delete action.
     * @param Request $request
     * @param Comment $comment
     * @return Response
     */
    #[Route('/delete/{id}', name: 'comment_delete', requirements: ['id' => '[1-9]\d*'], methods: 'GET|POST')]
    public function delete(Request $request, Comment $comment): Response
    {
        $form = $this->createForm(
            FormType::class,
            $comment,
            [
                'method' => 'POST',
                'action' => $this->generateUrl('comment_delete', ['id' => $comment->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->commentService->delete($comment);

            $this->addFlash(
                'success',
                $this->translator->trans('message.deleted_successfully')
            );

            return $this->redirectToRoute('comment_index');
        }

        return $this->render(
            'comment/delete.html.twig',
            [
                'form' => $form->createView(),
                'comment' => $comment,
            ]
        );
    }
}