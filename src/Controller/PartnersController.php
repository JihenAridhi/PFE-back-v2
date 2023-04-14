<?php

namespace App\Controller;

use App\Entity\Partners;
use App\Repository\PartnersRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PartnersController extends AbstractController
{

    private ManagerRegistry $managerRegistry;
    private PartnersRepository $repo;
    private ObjectManager $objectManager;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
        $this->repo = $this->managerRegistry->getRepository(Partners::class);
        $this->objectManager = $this->managerRegistry->getManager();
    }
    #[Route('/partners/getAll')]
    public function getAll(): Response
    {return $this->json($this->repo->findAll());}

    #[Route('/partners/get/{id}')]
    public function get(int $id): Response
    {return $this->json($this->repo->find($id));}

    #[Route('/partner/add')]
    public function add(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        $partner = new Feedback($data['name'], $data['type'], $data['description'], $data['urlpage']);
        $this->objectManager->persist($partner);

        $this->objectManager->flush();

        return $this->json('success');
    }


    #[Route('/partner/delete/{id}')]
    public function delete(int $id): Response
    {
        $this->repo->remove($id);
        return $this->json('success !!');
    }
}