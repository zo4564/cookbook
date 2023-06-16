<?php
/**
 * comment controller.
 */

namespace App\Controller;

use App\Entity\Comment;
use App\Repository\CommentRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class commentController.
 */
#[Route('/comment')]
class CommentController extends AbstractController
{
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
}