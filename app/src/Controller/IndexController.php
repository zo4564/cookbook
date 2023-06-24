<?php
/**
 * index controller
 */

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class indexController
 */

class IndexController extends AbstractController
{
    #[Route('/', name: 'index',)]
    public function index(): Response
    {
        return $this->redirectToRoute("recipe_index");
    }
}