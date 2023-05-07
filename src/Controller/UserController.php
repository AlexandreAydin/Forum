<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserPasswordType;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;


class UserController extends AbstractController
{
    #[Route('/utilisateur/edition/{id}', name: 'app_user')]
    public function user(
        User $choosenUser,
        Request $request, 
        EntityManagerInterface $manager,
        UserPasswordHasherInterface $hasher   
    ): Response
    {
        //on crée le formulaire
        $form = $this->createForm(UserType::class, $choosenUser);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            // si le mo de passe est correcte on autorise la modification
            if($hasher->isPasswordValid($choosenUser,$form->getData()->getPlainPassword())){
                $user = $form->getData();
                $manager->persist($user);
                $manager->flush();

                $this->addFlash(
                    'success',
                    "Les information de votre compte a bien été modifiées."
                );

                return $this->redirectToRoute('app_forum');
            }else{
                $this->addFlash(
                    "warning",
                    "Le mot de passe n'est pas correct."
                );
            }
        }

        return $this->render('pages/user/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/utilisateur/edition-mot-de-passe/{id}', name:'app_user.editPassword', methods:['GET','POST'] )]
    public function editPassword(
        User $choosenUser,
        Request $request,
        EntityManagerInterface $manger,
        UserPasswordHasherInterface $hasher
    ) : Response 
    {
        //on crée le formulaire
        $form = $this->createForm(UserPasswordType::class);

        $form -> handleRequest($request);
        if ($form->isSubmitted()&& $form->isValid()) {
        // si le mo de passe est correcte on autorise la modification
            if($hasher->isPasswordValid($choosenUser,$form->getData()['plainPassword'])){
                $choosenUser->setUpdatedAt(new \DateTimeImmutable());
                $choosenUser->setPlainPassword(
                    $form->getData()['newPassword']
                );

                $manger->persist($choosenUser);
                $manger->flush();

                $this->addFlash(
                    'succes',
                    'Votre mot de passe  a bien été modifier'
                );

                return $this->redirectToRoute('app_forum'); 
            } else{
                $this->addFlash(
                    'warning',
                    'Le mot de passe est incorrect.'
                );
            }          
    }

        return $this->render('pages/user/edit_password.html.twig', [
            'form' => $form->createView(),
        ]);    
    }
    
}
   