<?php

namespace App\Controller\Admin;

use App\Entity\Forum;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ForumCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Forum::class;
    }

   
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title'),
            TextField::new('description'),
            AssociationField::new('categories'),
            BooleanField::new('isFavorite'),
            BooleanField::new('isPublic'),
        ];
    }
    
}
