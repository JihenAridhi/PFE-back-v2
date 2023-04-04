<?php

namespace App\Controller;

use App\Entity\Autorisation;
use App\Entity\Person;
use App\Repository\PersonRepository;
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

    #[Route('/person/update')]
    public function update(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        dump($data['interest']);

        $person = $this->repo->find($data['id']);
        $person->setFirstName($data['firstName']);
        $person->setLastName($data['lastName']);
        $person->setEmail($data['email']);
        $person->setPassword($data['password']);
        $person->setProfession($data['profession']);
        $person->setTeam($data['team']);
        $person->setInterest($data['interest']);
        $person->setStatus(true);

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

    #[Route('/person/{id}/getAutorisations')]
    public function getPersonAutorisations($id)
    {
        $query = $this->repo->createQueryBuilder('p')
            ->select('a.autorisation')
            ->leftjoin('p.autorisations', 'a')
            ->andWhere('p.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();
        return $this->json($query);
    }

   #[Route('/person/{idP}/addAutorisation/{idA}')]
    public function addAurorisations(int $idP, int $idA)
   {
        $person = $this->repo->find($idP);
        $autorisation = $this->managerRegistry->getRepository(Autorisation::class)->find($idA);

        $person->getAutorisations()->add($autorisation);
        $autorisation->getPerson()->add($person);

        $this->objectManager->persist($person);
        $this->objectManager->persist($autorisation);

        $this->objectManager->flush();

        return $this->json('sucess!!');
   }

}
