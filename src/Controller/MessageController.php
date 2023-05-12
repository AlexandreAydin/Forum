<?php

namespace App\Controller;

use App\Entity\Messages;
use App\Form\MessagesType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MessageController extends AbstractController
{
    #[Route('/messages', name: 'app_messages')]
    public function messages(): Response
    {
        return $this->render('pages/messages/messages.html.twig', [
            'controller_name' => 'MessageController',
        ]);
    }

    #[Route('/send', name: 'app_send')]
    public function send(Request $request, EntityManagerInterface $em): Response
    {
        $message = new Messages;
        $form = $this->createForm(MessagesType::class, $message);
        
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $message->setSender($this->getUser());
        
            
            $em->persist($message);
            $em->flush();
        
            $this->addFlash("message", "Message envoyé avec succès.");
            return $this->redirectToRoute("app_messages");
        }

        return $this->render("pages/messages/send.html.twig", [
            "form" => $form->createView()
        ]);
    }

    #[Route('/received', name :'app_received')]
    public function received(): Response
    {
        return $this->render('pages/messages/received.html.twig');
    }

    #[Route('/sent', name :'app_sent')]
    public function sent(): Response
    {
        return $this->render('pages/messages/sent.html.twig');
    }

    #[Route('/read/{id}' , name:"app_read")]
    public function read(Messages $message,EntityManagerInterface $em): Response
    {
        $message->setIsRead(true);
 
        $em->persist($message);
        $em->flush();

        return $this->render('pages/messages/read.html.twig', compact("message"));
    }

    #[Route('/delete/{id}',name:'app_delete')]
    public function delete(Messages $message,EntityManagerInterface $em): Response
    {

        $em->remove($message);
        $em->flush();

        return $this->redirectToRoute("app_received");
    }


}
