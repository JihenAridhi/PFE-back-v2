<?php

namespace App\Controller;

use App\Entity\Autorisation;
use App\Repository\AutorisationRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AutorisationController extends AbstractController
{
    private ManagerRegistry $managerRegistry;
    private AutorisationRepository $repo;
    private ObjectManager $objectManager;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
        $this->repo = $this->managerRegistry->getRepository(Autorisation::class);
        $this->objectManager = $this->managerRegistry->getManager();
    }


    #[Route('/autorisation/getAll')]
    public function getAll(): Response
    {return $this->json($this->repo->findAll());}

    #[Route('/autorisation/get/{id}')]
    public function get(int $id): Response
    {return $this->json($this->repo->find($id));}

    /*#[Route('/autorisation/add')]
    public function add(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        $auto = new Autorisation($data['autorisation']);

        $this->objectManager->persist($auto);
        $this->objectManager->flush();

        return$this->json($auto);
    }*/

    #[Route('/autorisation/update/{id}')]
    public function update(int $id, Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        $auto = $this->repo->find($id);
        $auto->setFirstName($data['autorisation']);


        $this->objectManager->persist($auto);
        $this->objectManager->flush();

        return$this->json($auto);
    }

    #[Route('/autorisation/delete/{id}')]
    public function delete(int $id): Response
    {
        $this->objectManager->remove($this->repo->find($id));
        $this->objectManager->flush();
        return $this->json('success!!');
    }



}
