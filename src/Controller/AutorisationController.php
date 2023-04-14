<?php

namespace App\Controller;

use App\Entity\Autorisation;
use App\Entity\Person;
use App\Repository\AutorisationRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function Webmozart\Assert\Tests\StaticAnalysis\length;

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

    #[Route('/person/{id}/getAutorisations')]
    public function getPersonAutorisations($id)
    {
        $query = $this->repo->createQueryBuilder('a')
            ->select('a.id')
            ->leftjoin('a.person', 'p')
            ->andWhere('p.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();
        return $this->json($query);
    }

    #[Route('/autorisation/getAll')]
    public function getAll(): Response
    {
        $users = $this->managerRegistry->getRepository(Person::class)->createQueryBuilder('p')
            ->select('p.id as idP, a.id as idA')
            ->leftjoin('p.autorisations', 'a')
            ->andWhere('p.status=true')
            ->orderBy('p.id')
            ->getQuery()
            ->getResult();

        $query = array();
        foreach ($users as $row) {
            if (!$row['idA'])
                $query[$row['idP']] = [];
            else
            $query[$row['idP']][] = $row['idA'];
        }
        //$query = array_map(function ($row) {return array_values($row);}, $query);
        $query = array_values($query);
        return $this->json($query);
    }

    #[Route('/autorisation/get/{id}')]
    public function get(int $id): Response
    {return $this->json($this->repo->find($id));}

    #[Route('/person/{idP}/addAutorisation/{idA}')]
    public function addAurorisations(int $idP, int $idA)
    {
        $autorisation = $this->repo->find($idA);
        $person = $this->managerRegistry->getRepository(Person::class)->find($idP);

        $person->getAutorisations()->add($autorisation);
        $autorisation->getPerson()->add($person);

        $this->objectManager->persist($person);
        $this->objectManager->persist($autorisation);

        $this->objectManager->flush();

        return $this->json('sucess!!');
    }


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

    #[Route('/person/{idP}/deleteAutorisation/{idA}')]
    public function deletePerson(int $idP, int $idA)
    {
        $autorisation = $this->repo->find($idA);
        $person = $this->managerRegistry->getRepository(Person::class)->find($idP);

        $autorisation->getPerson()->removeElement($person);
        $person->getAutorisations()->removeElement($autorisation);

        $this->objectManager->persist($autorisation);
        $this->objectManager->persist($person);

        $this->objectManager->flush();

        return $this->json('success');
    }

}
