<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    #[Route('/preview/landshaftnoe-proektirovanie', name: 'preview_landshaftnoe_proektirovanie')]
    public function previewLandshaftnoeProektirovanie(): Response
    {
        return $this->render('page/landshaftnoe-proektirovanie.html.twig', [
            'controller_name' => 'PageController',
        ]);
    }

    #[Route('/{slug}', name: 'app_page', defaults: ['slug' => 'homepage'])]
    public function index(string $slug): Response
    {
        return $this->render("page/${slug}.html.twig", [
            'controller_name' => 'PageController',
        ]);
    }
}
