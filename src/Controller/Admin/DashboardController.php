<?php

namespace App\Controller\Admin;

use App\Entity\Categorie;
use App\Entity\Forum;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig');     
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Forum' ,'Administration')
            ->renderContentMaximized();
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('User', 'fas fa-bowl-rice', User::class);
        yield MenuItem::linkToCrud('Forum', 'fas fa-bowl-rice', Forum::class);
        yield MenuItem::linkToCrud('Catégorie', 'fas fa-bowl-rice', Categorie::class);
    }
}
