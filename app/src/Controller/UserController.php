<?php
/**
 * User controller.
 */

namespace App\Controller;

use App\Entity\User;
use App\Form\SignUpType;
use App\Form\UserType;
use App\Service\UserService;
use App\Repository\UserRepository;
use App\Service\UserServiceInterface;
use App\Service\CommentService;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use PHPUnit\Exception;
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
 * Class UserController.
 */
#[Route('/user')]
class UserController extends AbstractController
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
    #[Route(name: 'user_index', methods: 'GET')]
    public function index(Request $request, Security $security): Response
    {
        if (!in_array('ROLE_ADMIN', $security->getUser()->getRoles()))
            $this->redirectToRoute('recipe_index');
        $pagination = $this->userService->getPaginatedList(
            $request->query->getInt('page', 1)
        );

        return $this->render('user/index.html.twig', ['pagination' => $pagination]);
    }

    /**
     * Show action.
     *
     * @param User $user User
     *
     * @return Response HTTP response
     */
    #[Route(
        '/{id}',
        name: 'user_show',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET'
    )]
    public function show(Request $request, UserRepository $userRepository, CommentService $commentService, Security $security, User $user): Response
    {
        if (!in_array('ROLE_ADMIN', $security->getUser()->getRoles()))
            $this->redirectToRoute('recipe_index');

        $pagination = $commentService->getPaginatedListByUser($request->query->getInt('page', 1), $user);

        return $this->render(
            'user/show.html.twig',
            ['user' => $user, 'pagination' => $pagination]
        );
    }

    /*

     */
    #[Route(
        '/edit/{id}',
        name: 'user_edit',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET|POST'
    )]
    public function edit(Request $request, Security $security, User $user): Response
    {
        if (!in_array('ROLE_ADMIN', $security->getUser()->getRoles()))
            $this->redirectToRoute('recipe_index');

        $form = $this->createForm(UserType::class, $user,
        [
            'method' => 'POST',
            'action' => $this->generateUrl('user_edit', ['id' => $user->getId()])
            ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->userService->save($user);
            $this->addFlash('success', $this->translator->trans('user.edited_successfully'));
            return $this->redirectToRoute('recipe_index');
        }

        return $this->render(
            'user/edit.html.twig',
            ['user' => $user, 'form' => $form->createView()]);
    }

    /**
     * Create action.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response*/
    #[Route('/signup', name: 'user_signup', methods: 'GET|POST', )]
    public function create(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(
            SignUpType::class,
            $user,
            ['action' => $this->generateUrl('user_signup')]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            try {
            $this->userService->save($user);

            }
            catch (UniqueConstraintViolationException $exception){
                $this->addFlash(
                    'error',
                    $this->translator->trans('message.email_taken')
                );
                return $this->redirectToRoute('user_signup');
            }
            $this->addFlash(
                'success',
                $this->translator->trans('message.created_successfully')
            );

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/create.html.twig',  ['form' => $form->createView()]);
    }
    /**
     * Delete action.
     *
     * @param Request  $request  HTTP request
     * @param User $user User entity
     *
     * @return Response HTTP response
     */

    #[Route('/delete/{id}', name: 'user_delete', requirements: ['id' => '\d+'], methods: ['GET', 'DELETE'])]
    public function delete(Request $request, Security $security, User $user): Response
    {
        if (!in_array('ROLE_ADMIN', $security->getUser()->getRoles()))
            $this->redirectToRoute('recipe_index');
        $form = $this->createForm(FormType::class, $user, [
            'method' => 'DELETE',
            'action' => $this->generateUrl('user_delete', ['id' => $user->getId()]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->userService->delete($user);

            $this->addFlash(
                'success',
                $this->translator->trans('message.deleted_successfully')
            );

            return $this->redirectToRoute('user_index');
        }

        return $this->render(
            'user/delete.html.twig',
            [
                'form' => $form->createView(),
                'user' => $user,
            ]
        );
    }
}
