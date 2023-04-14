<?php

namespace App\Controller;

use App\Entity\Feedback;
use App\Repository\FeedbackRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FeedbackController extends AbstractController
{
    private ManagerRegistry $managerRegistry;
    private FeedbackRepository $repo;
    private ObjectManager $objectManager;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
        $this->repo = $this->managerRegistry->getRepository(Feedback::class);
        $this->objectManager = $this->managerRegistry->getManager();
    }
    #[Route('/feedback/getAll')]
    public function getAll(): Response
    {return $this->json($this->repo->findAll());}

    #[Route('/feedback/get/{id}')]
    public function get(int $id): Response
    {return $this->json($this->repo->find($id));}

    #[Route('/feedback/add')]
    public function add(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        $feedback = new Feedback($data['name'], $data['email'], $data['date'], $data['message'], $data['phone']);
        $this->objectManager->persist($feedback);

        $this->objectManager->flush();

        return $this->json('success');
    }

}