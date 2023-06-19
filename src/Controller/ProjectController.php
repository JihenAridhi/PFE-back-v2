<?php

namespace App\Controller;

use App\Entity\Project;
use App\Repository\ProjectRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProjectController extends AbstractController
{
    private ManagerRegistry $managerRegistry;
    private ProjectRepository $repo;
    private ObjectManager $objectManager;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
        $this->repo = $this->managerRegistry->getRepository(Project::class);
        $this->objectManager = $this->managerRegistry->getManager();
    }

    #[Route('/project/getAll')]
    public function getAll(): Response
    {
        $projectList = $this->repo->findAll();
        foreach ($projectList as $project)
            $project->setPhoto($this->getPhoto($project->getId()));
            $project->setPartners();
        return $this->json($projectList);
    }

    #[Route('/project/get/{id}')]
    public function get(int $id): Response
    {
        $project = $this->repo->find($id);
        $project->setPartners();
        $project->setPhoto($this->getPhoto($id));
        return $this->json($project);
    }
    #[Route('/project/getAll/{id}')]
    public function getPartnerProjects(int $id): Response
    {
        $projects = $this->repo->createQueryBuilder('a')
            ->leftJoin('a.projectPartner', 'p')
            ->andWhere('p.partner=:id')
            ->setParameter(':id', $id)
            ->getQuery()
            ->getResult();
        foreach ($projects as $project)
            $project->setPartners();
        return $this->json($projects);
    }

    #[Route('/project/add')]
    public function add(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        $project = new project();
        $project->setTitle($data['title']);
        $project->setTitle($data['date']);
        $project->setDescription($data['description']);
        $project->setType($data['type']);

        $this->objectManager->persist($project);
        $this->objectManager->flush();
        $this->setPartners($project, $data['partner']);

        return $this->json($project);
    }

    #[Route('/project/update')]
    public function update(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        $project = $this->repo->find($data['id']);

        $project->setTitle($data['title']);
        //$project->setDate($data['date']);
        $project->setDescription($data['description']);
        $project->setType($data['type']);
        //$project->setPhoto($data['photo']);

        $this->objectManager->persist($project);
        $this->objectManager->flush();
        $this->setPartners($project, $data['partners']);

        return $this->json($project);
    }

    #[Route('/project/delete/{id}')]
    public function delete(int $id): Response
    {
        $this->repo->remove($this->repo->find($id));
        $this->objectManager->flush();
        return $this->json('success !!');
    }

    #[Route('photo/project')]
    public function upload(Request $request): Response
    {
        $server = 'C:\Users\ARIDHI\Desktop\PFE\PFE-front\src\\';
        $file = $request->files->get('file');
        $fileName = $file->getClientOriginalName();

        try {$file->move($server.'assets\projectPhoto\\', $fileName);}
        catch (FileException $e) {}

        return $this->json('');
    }

    public function getPhoto(int $id)
    {
        $server = 'C:\Users\ARIDHI\Desktop\PFE\PFE-front\src\\';
        $path = $server."assets\\projectPhoto\\";
        if (file_exists($path.$id.'.jpg'))
            return "assets\\projectPhoto\\".$id.'.jpg';
        return "assets\\projectPhoto\\".'default.jpg';
    }
    #[Route('/project/{id}/getPartners')]
    public function getPartners(int $id): Response
    {
        $project = $this->repo->find($id);
        return $this->json($project->getPartners());
    }
    public function setPartners(array $partners, Project $project): Response
    {
        foreach ($project->getPartners() as $partner)
        {
            $partner->getProjects()->removeElement($project);
            $project->getPartners()->removeElement($partner);
            $this->objectManager->persist($partner);
        }

        foreach ($partners as $partner)
        {
            $partner = $this->objectManager->getRepository(Partners::class)->find($partner['id']);
            $partner->getProjects()->add($project);
                $project->getPartners()->add($partner);
            $this->objectManager->persist($partner);
        }
        $this->objectManager->persist($project);
        $this->objectManager->flush();
        return $this->json($project);
    }
}
