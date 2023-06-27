<?php
/**
 * UserPanel controller.
 */

namespace App\Controller;

use App\Entity\User;
use App\Form\SignUpType;
use App\Form\UserType;
use App\Service\UserService;
use App\Repository\UserRepository;
use App\Service\UserServiceInterface;
use App\Service\CommentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface;
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
     */
    public function __construct(UserService $userService, TranslatorInterface $translator)
    {
        $this->userService = $userService;
        $this->translator = $translator;
    }

    /**
     * Index action.
     *
     * @param Request $request HTTP Request
     *
     * @return Response HTTP response
     */
    #[Route(name: 'user_panel_index', methods: 'GET')]
    public function index(Request $request, UserRepository $userRepository, CommentService $commentService, Security $security): Response
    {
        $user = $security->getUser();
        $pagination = $commentService->getPaginatedListByUser($request->query->getInt('page', 1), $user);

        return $this->render(
            'user/show.html.twig',
            ['user' => $user, 'pagination' => $pagination]
        );    }
}