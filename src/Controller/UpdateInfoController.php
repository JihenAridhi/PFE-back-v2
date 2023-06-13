<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UpdateInfoController extends AbstractController
{
    #[Route('/update/info', name: 'app_update_info')]
    public function index(): Response
    {
        return $this->render('update_info/index.html.twig', [
            'controller_name' => 'UpdateInfoController',
        ]);
    }
}
