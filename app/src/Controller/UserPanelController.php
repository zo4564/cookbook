<?php

/**
 * UserPanel controller.
 */

namespace App\Controller;

use App\Entity\User;
use App\Form\UserNameType;
use App\Form\UserPasswordType;
use App\Repository\UserRepository;
use App\Service\CommentService;
use App\Service\UserServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class UserPanelController.
 */
#[Route('/panel')]
class UserPanelController extends AbstractController
{
    /**
     * User service.
     */
    private UserServiceInterface $userService;

    /**
     * Translator.
     */
    private TranslatorInterface $translator;

    /**
     * Constructor.
     *
     * @param UserServiceInterface $userService
     * @param TranslatorInterface  $translator
     */
    public function __construct(UserServiceInterface $userService, TranslatorInterface $translator)
    {
        $this->userService = $userService;
        $this->translator = $translator;
    }

    /**
     * Index action.
     *
     * @param Request        $request        HTTP Request
     * @param UserRepository $userRepository User repository
     * @param CommentService $commentService Comment service
     * @param Security       $security       Security
     *
     * @return Response HTTP response
     */
    #[Route(name: 'user_panel_index', methods: 'GET')]
    public function index(Request $request, UserRepository $userRepository, CommentService $commentService, Security $security): Response
    {
        $user = $security->getUser();
        $pagination = $commentService->getPaginatedListByUser($request->query->getInt('page', 1), $user);

        return $this->render(
            'user/panel.html.twig',
            ['user' => $user, 'pagination' => $pagination]
        );
    }

    /**
     * Edit name action.
     *
     * @param Request  $request  HTTP request
     * @param Security $security Security
     *
     * @return Response HTTP response
     */
    #[Route(
        '/editName',
        name: 'panel_edit_name',
        methods: 'GET|POST'
    )]
    public function editName(Request $request, Security $security): Response
    {
        $user = $security->getUser();

        $form = $this->createForm(
            UserNameType::class,
            $user,
            [
                'method' => 'POST',
                'action' => $this->generateUrl('panel_edit_name', ['id' => $user->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->userService->save($user);
            $this->addFlash('success', $this->translator->trans('user.edited_successfully'));

            return $this->redirectToRoute('user_panel_index');
        }

        return $this->render(
            'user/edit.html.twig',
            ['user' => $user, 'form' => $form->createView()]
        );
    }

    /**
     * Edit password action.
     *
     * @param Request  $request  HTTP request
     * @param Security $security Security
     *
     * @return Response HTTP response
     */
    #[Route(
        '/editPassword',
        name: 'panel_edit_password',
        methods: 'GET|POST'
    )]
    public function editPassword(Request $request, Security $security): Response
    {
        $user = $security->getUser();

        $form = $this->createForm(
            UserPasswordType::class,
            $user,
            [
                'method' => 'POST',
                'action' => $this->generateUrl('panel_edit_password', ['id' => $user->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->userService->save($user);
            $this->addFlash('success', $this->translator->trans('user.edited_successfully'));

            return $this->redirectToRoute('user_panel_index');
        }

        return $this->render(
            'user/edit.html.twig',
            ['user' => $user, 'form' => $form->createView()]
        );
    }
}
