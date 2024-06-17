<?php

namespace App\Controller;

use App\Repository\SystemRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(SystemRepository $systemRepository): Response
    {
        $systems = $systemRepository->findAll();

        return $this->render('index/index.html.twig', [
            'systems' => $systems,
        ]);
    }
}
