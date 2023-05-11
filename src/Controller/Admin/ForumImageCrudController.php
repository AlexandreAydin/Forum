<?php

namespace App\Controller\Admin;

use App\Entity\ForumImage;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ForumImageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ForumImage::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
