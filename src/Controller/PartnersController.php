<?php

namespace App\Controller;

use App\Entity\Partners;
use App\Repository\PartnersRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
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
    #[Route('/partner/getAll')]
    public function getAll(): Response
    {
        $partnerList = $this->repo->findAll();
        foreach ($partnerList as $partner)
            $partner->setPhoto($this->getPhoto($partner->getId()));
        return $this->json($partnerList);
    }

    #[Route('/partner/get/{id}')]
    public function get(int $id): Response
    {return $this->json($this->repo->find($id));}

    #[Route('/project/{id}/getPartners')]
    public function getProjectPartners($id)
    {
        $query = $this->repo->createQueryBuilder('a')
            ->select('a.id')
            ->leftjoin('a.projects', 'p')
            ->andWhere('p.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();
        return $this->json($query);
    }
    #[Route('/project/{idP}/addPartner/{idT}')]
    public function addPartners(int $idP, int $idT)
    {
        $partner = $this->repo->find($idT);
        $project = $this->managerRegistry->getRepository(Project::class)->find($idP);

        $project->getPartners()->add($partner);
        $partner->getProjects()->add($project);

        $this->objectManager->persist($project);
        $this->objectManager->persist($partner);

        $this->objectManager->flush();

        return $this->json('sucess!!');
    }
    #[Route('/project/{idP}/deletePartner/{idT}')]
    public function deletePartner(int $idP, int $idT)
    {
        $partner = $this->repo->find($idT);
        $project = $this->managerRegistry->getRepository(Project::class)->find($idP);

        $partner->getProjects()->removeElement($project);
        $project->getPartners()->removeElement($partner);

        $this->objectManager->persist($partner);
        $this->objectManager->persist($project);

        $this->objectManager->flush();

        return $this->json('success');
    }

    #[Route('/partner/add')]
    public function add(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        $partner = new Partners($data['name'], $data['type'], $data['description'], $data['urlPage']);
        $this->objectManager->persist($partner);

        $this->objectManager->flush();

        return $this->json($partner->getId());
    }

    #[Route('/partner/update')]
    public function update(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        $partner = $this->repo->find($data['id']);
        $partner->setType($data['type']);
        $partner->setDescription($data['description']);
        $partner->setName($data['name']);
        $partner->setUrlPage($data['urlPage']);

        $this->objectManager->persist($partner);

        $this->objectManager->flush();

        return $this->json('success');
    }

    #[Route('/partner/delete/{id}')]
    public function delete(int $id): Response
    {
        $this->objectManager->remove($this->repo->find($id));
        $this->objectManager->flush();
        return $this->json('success !!');
    }

    #[Route('photo/partner')]
    public function upload(Request $request): Response
    {
        $server = 'C:\Users\ARIDHI\Desktop\PFE - Copy\PFE-front\src\\';
        $file = $request->files->get('file');
        $fileName = $file->getClientOriginalName();

        try {$file->move($server.'assets\partnerPhoto\\', $fileName);}
        catch (FileException $e) {}

        return $this->json('assets\partnerPhoto\\'.$fileName);
    }

    //#[Route('photo/partner/get/{id}')]
    public function getPhoto(int $id): string
    {
        $server = 'C:\Users\ARIDHI\Desktop\PFE - Copy\PFE-front\src\\';
        $path = $server."assets\partnerPhoto\\";
        if (file_exists($path.$id.'.jpg'))
            return "assets/partnerPhoto/".$id.'.jpg';
        return 'assets/partnerPhoto/default.jpg';
    }
}
