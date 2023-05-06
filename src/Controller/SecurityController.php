<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{

    #[Route('/inscription', name: 'app_registration')]
    public function registration(Request $request, EntityManagerInterface $manager ): Response
    {
        $user= new User();
        $user->setRoles(['ROLE_USER']);

        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $user =$form->getData();
            
            $this->addFlash(
                "success",
                "Votre compte a bien été créé"
            );

            $manager -> persist ($user);
            $manager->flush();

            return $this->redirectToRoute('app_login');
        }

        return $this ->render('pages/security/registration.html.twig',[
            'form' => $form->createView(),
        ]);

    }


    #[Route('/connexion', name: 'app_login')]
    public function login(): Response
    {
        return $this->render('pages/security/login.html.twig', [
            'controller_name' => 'SecurityController',
        ]);
    }

    #[Route('/connexion', name: 'app_logout')]
    public function logout(): Response
    {
        return $this->render('pages/security/login.html.twig', [
            'controller_name' => 'SecurityController',
        ]);
    }


}
