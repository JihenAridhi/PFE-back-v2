<?php

namespace App\Controller;

use App\Entity\News;
use App\Repository\NewsRepository;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class NewsController extends AbstractController
{
    private ManagerRegistry $managerRegistry;
    private NewsRepository $repo;
    private ObjectManager $objectManager;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
        $this->repo = $this->managerRegistry->getRepository(News::class);
        $this->objectManager = $this->managerRegistry->getManager();
    }

    #[Route('/news/getAll')]
    public function getAll(): Response
    {return $this->json($this->repo->findAll());}

    #[Route('/news/get/{id}')]
    public function get(int $id): Response
    {
        $news = $this->repo->find($id);
        return $this->json($news);
    }

    #[Route('/news/add')]
    public function add(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        $news = new News($data['title'], $data['date'], $data['description']);

        $this->objectManager->persist($news);
        $this->objectManager->flush();

        return $this->json($news);
    }

    #[Route('/news/update')]
    public function update(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        $news = $this->repo->find($data['id']);

        $news->setTitle($data['title']);
        $news->setDate(DateTime::createFromFormat('d-m-Y H:i:s', $data['date']));
        $news->setDescription($data['description']);
        //$news->setPhoto($data['photo']);

        $this->objectManager->persist($news);
        $this->objectManager->flush();

        return $this->json($news);
    }

    #[Route('/news/delete/{id}')]
    public function delete(int $id): Response
    {
        $this->repo->remove($this->repo->find($id));
        $this->objectManager->flush();
        return $this->json('success !!');
    }

    #[Route('photo/news')]
    public function upload(Request $request): Response
    {
        $server = 'C:\Users\ARIDHI\Desktop\PFE\PFE-front\src\\';
        $file = $request->files->get('file');
        $fileName = $file->getClientOriginalName();

        try {$file->move($server.'assets\newsPhoto\\', $fileName);}
        catch (FileException $e) {}

        return $this->json('');
    }

    #[Route('photo/news/get/{id}')]
    public function getPhoto(int $id)
    {
        $server = 'C:\Users\ARIDHI\Desktop\PFE\PFE-front\src\\';
        $path = $server."assets\\newsPhoto\\";
        if (file_exists($path.$id.'.jpg'))
            return $this->json("assets\\newsPhoto\\".$id.'.jpg');
        return $this->json("assets\\newsPhoto\\".'default.jpg');
    }
}
