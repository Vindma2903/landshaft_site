<?php

namespace App\Controller\Admin;

use App\Entity\Page;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\CodeEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Page::class;
    }

    public function createEntity(string $entityFqcn)
    {
        $page = new Page();
        $page->setCreatedAt(new \DateTime());
        $page->setUpdatedAt(new \DateTime());

        return $page;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextField::new('template'),
            SlugField::new('slug')->setTargetFieldName('title'),
            CodeEditorField::new('body'),



            TextField::new('metaTitle'),
            TextField::new('metaDescription'),
            TextField::new('metaKeywords'),
        ];
    }
}
