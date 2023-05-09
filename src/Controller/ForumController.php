<?php

namespace App\Controller;

use App\Entity\Forum;
use App\Entity\Mark;
use App\Entity\User;
use App\Form\ForumType;
use App\Form\MarkType;
use App\Repository\ForumRepository;
use App\Repository\MarkRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ForumController extends AbstractController
{
    #[IsGranted('ROLE_USER')]
    #[Route('/forum', name: 'app_forum')]
    public function forum(
    ForumRepository $repository,
    PaginatorInterface $paginator,
    Request $request
    ): Response
    {
        $forums = $paginator->paginate(
            $repository->findBy(['user' => $this->getUser()]),
            $request->query->getInt('page', 1),
            10 
        );   

        return $this->render('pages/forum/forum.html.twig', [
            "forums"=> $forums
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/forum/creation', name:"app_new_forum", methods:['GET', 'POST'])]
    public function new_forum(Request $request, EntityManagerInterface $manager): Response
    {
        $forum = new Forum();
        $forum->setUser($this->getUser());
        $form=$this->createForm(ForumType::class, $forum);

        $form->handleRequest($request);

        if($form->isSubmitted()&&$form->isValid()){
            $forum = $form->getData();

            $manager->persist($forum);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre forum a été créé avec succès!'
            );

            return $this->redirectToRoute('app_forum');
        }

        return $this->render('pages/forum/new_forum.html.twig', [
            'form'=> $form->createView(),
        ]);
    }

   
    #[Security("is_granted('ROLE_USER') and forum.isIsPublic() === true || user === forum.getUser()")]
    #[Route('/forum/{id}', name:"app_forum_show", methods:['GET', 'POST'])]
    public function forum_show(
        Forum $forum,
        Request $request,
        MarkRepository $markRepository,
        EntityManagerInterface $manager,
    ) : Response
    {
        $mark= new Mark();
        $form = $this->createForm(MarkType::class, $mark);
        
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            // on prend l'utilisateur courents 
            $mark->setUser($this->getUser())
                //on prend la recette courent
                ->setForum($forum);



            // un utilisateur ne peu pas voter 2 fois 
            $existingMark = $markRepository->findOneBy([
                'user'=>$this->getUser(),
                'forum'=>$forum
            ]);

            //si l'utilisateur n'a pas déja voté il peu voté 
            if(!$existingMark){
                $manager->persist ($mark);
            }else {
                // Il peu modifier sa note
                $existingMark->setMark(
                    $form->getData()->getMark()
                );

            }

            $manager->flush();

            $this->addFlash(
                'success',
                'Votre note a bien été prise en compte'
            );
            return $this->redirectToRoute('app_forum_show', ['id'=> $forum->getId()]);
        }

        return $this->render('pages/forum/show.html.twig',[
            "forum"=>$forum,
            "form"=>$form->createView()
        ]);
    }
    #[Security("is_granted('ROLE_USER') and user === forum.getUser()")]
    #[Route('/forum/edition/{id}', name:'app_forum_edit', methods:['GET', 'POST'])]
    public function forum_edit(
        Request $request,
        Forum $forum,
        EntityManagerInterface $manager,
    ) :  Response
    {
        $form = $this->createForm(ForumType::class, $forum);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $forum = $form->getData();
            $forum->setUser($this->getUser());

            $this->addFlash(
                'success',
                'Votre formulaire a bien été modifié'
            );

            $manager->persist($forum);
            $manager->flush();

            return $this->redirectToRoute('app_forum');
        }
        return $this->render('pages/forum/edit_forum.html.twig',[
            'form' => $form->createView(),
        ]);
    }

    #[Security("is_granted('ROLE_USER') and user === forum.getUser()")]
    #[Route('forum/suppression/{id}', name:'app_forum_delete' , methods: (['GET']))]
    public function forum_delet(
        EntityManagerInterface $manager,
        Forum $forum
    ) : Response
    {
        $manager->remove($forum);
        $manager->flush();

        $this->addFlash(
            'succes',
            'Votre forum a bien été supprimé'
        );

        return $this->redirectToRoute('app_forum');
    }
    
}
