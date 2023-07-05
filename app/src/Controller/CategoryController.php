<?php
/**
 * Category controller.
 */

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Service\CategoryServiceInterface;
use App\Repository\CategoryRepository;
use App\Service\RecipeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class CategoryController.
 *
 * @Route("/category")
 */
class CategoryController extends AbstractController
{
    /**
     * Category service.
     */
    private CategoryServiceInterface $categoryService;

    /**
     * Translator.
     */
    private TranslatorInterface $translator;

    /**
     * CategoryRepository.
     */
    private CategoryRepository $categoryRepository;

    /**
     * Constructor.
     *
     * @param CategoryServiceInterface $categoryService
     * @param TranslatorInterface      $translator
     * @param CategoryRepository       $categoryRepository
     */
    public function __construct(CategoryServiceInterface $categoryService, TranslatorInterface $translator, CategoryRepository $categoryRepository)
    {
        $this->categoryService = $categoryService;
        $this->translator = $translator;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Index action.
     *
     * @Route("/", name="category_index", methods={"GET"})
     *
     * @param Request $request HTTP Request
     *
     * @return Response HTTP response
     */
    public function index(Request $request): Response
    {
        $pagination = $this->categoryService->getPaginatedList(
            $request->query->getInt('page', 1)
        );

        return $this->render('category/index.html.twig', ['pagination' => $pagination]);
    }

    /**
     * Show action.
     *
     * @Route("/{id}", name="category_show", requirements={"id"="\d+"}, methods={"GET"})
     *
     * @param Request       $request       HTTP request
     * @param Category      $category      Category entity
     * @param RecipeService $recipeService Recipe service
     * @param int           $id            Category ID
     *
     * @return Response HTTP response
     */
    public function show(Request $request, Category $category, RecipeService $recipeService, int $id): Response
    {
        $pagination = $recipeService->getPaginatedListByCategory($request->query->getInt('page', 1), $category);

        return $this->render(
            'category/show.html.twig',
            ['category' => $category, 'pagination' => $pagination]
        );
    }

    /**
     * Create action.
     *
     * @Route("/create", name="category_create", methods={"GET", "POST"})
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     */
    public function create(Request $request): Response
    {
        $category = new Category();
        $form = $this->createForm(
            CategoryType::class,
            $category,
            ['action' => $this->generateUrl('category_create')]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->categoryService->save($category);

            $this->addFlash(
                'success',
                $this->translator->trans('message.created_successfully')
            );

            return $this->redirectToRoute('category_index');
        }

        return $this->render('category/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * Edit action.
     *
     * @Route("/edit/{id}", name="category_edit", requirements={"id"="\d+"}, methods={"GET", "POST"})
     *
     * @param Request  $request  HTTP request
     * @param Category $category Category entity
     *
     * @return Response HTTP response
     */
    public function edit(Request $request, Category $category): Response
    {
        $form = $this->createForm(
            CategoryType::class,
            $category,
            [
                'method' => 'POST',
                'action' => $this->generateUrl('category_edit', ['id' => $category->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->categoryService->save($category);

            $this->addFlash(
                'success',
                $this->translator->trans('message.deleted_successfully')
            );

            return $this->redirectToRoute('category_index');
        }

        return $this->render(
            'category/create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Delete action.
     *
     * @Route("/delete/{id}", name="category_delete", requirements={"id"="\d+"}, methods={"GET", "POST"})
     *
     * @param Request  $request  HTTP request
     * @param Category $category Category entity
     *
     * @return Response HTTP response
     */
    public function delete(Request $request, Category $category): Response
    {
        $form = $this->createForm(
            FormType::class,
            $category,
            [
                'method' => 'POST',
                'action' => $this->generateUrl('category_delete', ['id' => $category->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->categoryService->delete($category);

            $this->addFlash(
                'success',
                $this->translator->trans('message.deleted_successfully')
            );

            return $this->redirectToRoute('category_index');
        }

        return $this->render(
            'category/delete.html.twig',
            [
                'form' => $form->createView(),
                'recipe' => $category,
            ]
        );
    }
}
