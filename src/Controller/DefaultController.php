<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/blog/default", name="app_index")
     */

    public function index()
    {
        return $this->render('blog/default.html.twig');
    }
}