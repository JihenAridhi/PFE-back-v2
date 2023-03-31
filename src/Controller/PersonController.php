<?php

namespace App\Controller;

use App\Entity\Person;
use App\Repository\PersonRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PersonController extends AbstractController
{
    private ManagerRegistry $managerRegistry;
    private PersonRepository $repo;
    private ObjectManager $objectManager;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
        $this->repo = $this->managerRegistry->getRepository(Person::class);
        $this->objectManager = $this->managerRegistry->getManager();
    }

    /*#[Route('/person', name: 'app_person')]
    public function index(): Response
    {
        return $this->render('person/index.html.twig', [
            'controller_name' => 'PersonController',
        ]);
    }*/

    #[Route('/person/getAll')]
    public function getAll(): Response
    {return $this->json($this->repo->findAll());}

    #[Route('/person/get/{id}')]
    public function get(int $id): Response
    {return $this->json($this->repo->find($id));}

    #[Route('/person/getByEmail/{email}')]
    public function getByEmail(string $email): Response
    {return $this->json($this->repo->findOneByEmail($email));}

    #[Route('/person/add')]
    public function add(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        $person = new Person($data['firstName'], $data['lastName'], $data['email'], $data['password']);

        $this->objectManager->persist($person);
        $this->objectManager->flush();

        return$this->json($person);
    }

    #[Route('/person/update/{id}')]
    public function update(int $id, Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        $person = $this->repo->find($id);
        $person->setFirstName($data['firstName']);
        $person->setLastName($data['lastName']);
        $person->setEmail($data['email']);
        $person->setPassword($data['password']);
        $person->setProfession($data['profession']);
        $person->setTeam($data['team']);
        $person->setInterest($data['interest']);

        $this->objectManager->persist($person);
        $this->objectManager->flush();

        return$this->json($person);
    }

    #[Route('/person/delete/{id}')]
    public function delete(int $id): Response
    {
        $this->objectManager->remove($this->repo->find($id));
        $this->objectManager->flush();
        return $this->json('success!!');
    }
/*
    #[Route('/person/{id}/autorisations')]
    public function getAutorisations(int $id, EntityManager $entityManager): Response
    {
        $query = $entityManager->createQuery
        ('select autorisation from autorisation A, autorisation_person AP where person_id = :id and A.id = AP.autorisation_id')
            ->setParameter('id', $id);

        return $this->json($query->getResult());
    }*/

}
