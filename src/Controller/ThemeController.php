<?php

namespace App\Controller;

use App\Entity\Person;
use App\Entity\Theme;
use App\Repository\ThemeRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ThemeController extends AbstractController
{
    private ManagerRegistry $managerRegistry;
    private ThemeRepository $repo;
    private ObjectManager $objectManager;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
        $this->repo = $this->managerRegistry->getRepository(Theme::class);
        $this->objectManager = $this->managerRegistry->getManager();
    }

    #[Route('/person/{id}/getThemes')]
    public function getPersonThemes($id)
    {
        $query = $this->repo->createQueryBuilder('t')
            ->select('t.id')
            ->leftjoin('t.person', 'p')
            ->andWhere('p.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();
        return $this->json($query);
    }

    #[Route('/theme/getAll')]
    public function getAll(): Response
    {
        return $this->json($this->repo->findAll());
    }

    #[Route('/theme/get/{id}')]
    public function get(int $id): Response
    {
        return $this->json($this->repo->find($id));
    }

    #[Route('/theme/add')]
    public function add(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $theme = new Theme();
        $theme->setTitle($data['title']);
        $this->objectManager->persist($theme);
        $this->objectManager->flush();
        return $this->json($theme);
    }

    #[Route('/person/{idP}/addTheme/{idT}')]
    public function addThemes(int $idP, int $idT)
    {
        $theme = $this->repo->find($idT);
        $person = $this->managerRegistry->getRepository(Person::class)->find($idP);

        $person->getThemes()->add($theme);
        $theme->getPerson()->add($person);

        $this->objectManager->persist($person);
        $this->objectManager->persist($theme);

        $this->objectManager->flush();

        return $this->json('sucess!!');
    }


    #[Route('/theme/update/{id}')]
    public function update(int $id, Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        $auto = $this->repo->find($id);
        $auto->setTitle($data['theme']);


        $this->objectManager->persist($auto);
        $this->objectManager->flush();

        return $this->json($auto);
    }

    #[Route('/theme/delete/{id}')]
    public function delete(int $id): Response
    {
        $this->objectManager->remove($this->repo->find($id));
        $this->objectManager->flush();
        return $this->json('success!!');
    }

    #[Route('/person/{idP}/deleteTheme/{idT}')]
    public function deletePerson(int $idP, int $idT)
    {
        $theme = $this->repo->find($idT);
        $person = $this->managerRegistry->getRepository(Person::class)->find($idP);

        $theme->getPerson()->removeElement($person);
        $person->getThemes()->removeElement($theme);

        $this->objectManager->persist($theme);
        $this->objectManager->persist($person);

        $this->objectManager->flush();

        return $this->json('success');
    }

}

