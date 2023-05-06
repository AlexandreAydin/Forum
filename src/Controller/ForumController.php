<?php

namespace App\Controller;

use App\Entity\Forum;
use App\Form\ForumType;
use App\Repository\ForumRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ForumController extends AbstractController
{
    #[Route('/forum', name: 'app_forum')]
    public function forum(ForumRepository $repository): Response
    {
        $forums = $repository->findAll();

        return $this->render('pages/forum/forum.html.twig', [
            "forums"=> $forums
        ]);
    }


    #[Route('/forum/creation', name:"app_new_forum", methods:['GET', 'POST'])]
    public function new_forum(Request $request, EntityManagerInterface $manager): Response
    {
        $forum = new Forum();

        $form=$this->createForm(ForumType::class, $forum);

        $form->handleRequest($request);

        if($form->isSubmitted()&&$form->isValid()){
            $forum = $form->getData();

            $manager->persist($forum);
            $manager->flush();

            return $this->redirectToRoute('app_forum');
        }

        return $this->render('pages/forum/new_forum.html.twig', [
            'form'=> $form->createView(),
        ]);
    }
}
