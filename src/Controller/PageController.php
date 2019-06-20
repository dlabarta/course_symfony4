<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class PageController extends AbstractController
{
    /**
     * @Route("/", name="app_index", methods={"GET"})
     */
    public function index(Request $request): Response
    {
        $user = $this->getUser();
        if ($user) {
            return $this->redirectToRoute('issue_index');
        }

        return $this->redirectToRoute('app_login');
    }
}
