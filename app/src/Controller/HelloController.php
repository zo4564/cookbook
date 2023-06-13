<?php
/**
 * Hello controller.
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class HelloController.
 */
class HelloController extends AbstractController
{
    /**
     * Index action.
     *
     * @param string $name User input
     *
     * @return Response HTTP response
     */
    public function index(string $name): Response
    {
        if(isset($_GET['name'])) $name = $_GET['name'];
        return $this->render(
            'hello/index.html.twig',
            ['name' => $name]
        );
    }
}