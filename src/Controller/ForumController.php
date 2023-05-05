<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ForumController extends AbstractController
{
    #[Route('/forum', name: 'app_forum')]
    public function forum(): Response
    {

        return $this->render('pages/forum/forum.html.twig', [
            'controller_name' => 'ForumController',
        ]);
    }
}
