<?php

namespace App\Controller\Admin;

use App\Entity\Forum;
use App\Form\ForumImageType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Doctrine\ORM\EntityManagerInterface;

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
            CollectionField::new('images')
                ->setEntryType(ForumImageType::class)
        ];
    }

    //pour pouvoir supprimer des forum par admin
    public function deleteEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof Forum) {
            foreach ($entityInstance->getCategories() as $category) {
                $entityInstance->removeCategory($category);
            }
    
            $entityManager->flush();
        }
    
        parent::deleteEntity($entityManager, $entityInstance);
    }
    
    
}
