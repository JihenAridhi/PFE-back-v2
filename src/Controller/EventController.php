<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Event;
use App\Entity\Person;
use App\Repository\ArticleRepository;
use App\Repository\EventRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EventController extends AbstractController
{
    private ManagerRegistry $managerRegistry;
    private EventRepository $repo;
    private ObjectManager $objectManager;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
        $this->repo = $this->managerRegistry->getRepository(Event::class);
        $this->objectManager = $this->managerRegistry->getManager();
    }
    #[Route('/event/getAll')]
    public function getAll(): Response
    {return $this->json($this->repo->findAll());}

    #[Route('/event/get/{id}')]
    public function get(int $id): Response
    {return $this->json($this->repo->find($id));}

    #[Route('/event/add')]
    public function add(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        $event = new Event($data['title'], $data['date'], $data['location'], $data['organiser'], $data['description']);

        $this->objectManager->persist($event);

        $this->objectManager->flush();

        return $this->json('success');
    }

    #[Route('/event/update')]
    public function update(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        $event = $this->repo->find($data['id']);
        $event->setTitle($data['title']);
        $event->setDate($data['date']);
        $event->setLocation($data['location']);
        $event->setDescription($data['description']);
        $event->setOrganiser($data['organiser']);

        return $this->json($event);
    }

    #[Route('/event/delete/{id}')]
    public function delete(int $id): Response
    {
        $this->repo->remove($this->repo->find($id));
        $this->objectManager->flush();
        return $this->json('success !!');
    }

    #[Route('photo/event')]
    public function upload(Request $request): Response
    {
        $server = 'C:\Users\ARIDHI\Desktop\PFE\PFE-front\src\\';
        $file = $request->files->get('file');
        $fileName = $file->getClientOriginalName();

        try {$file->move($server.'assets\eventPhoto\\', $fileName);}
        catch (FileException $e) {}

        return $this->json('');
    }

    #[Route('photo/event/get/{id}')]
    public function getPhoto(int $id)
    {
        $server = 'C:\Users\ARIDHI\Desktop\PFE\PFE-front\src\\';
        $path = $server."assets\\eventPhoto\\";
        if (file_exists($path.$id.'.jpg'))
            return $this->json("assets\\eventPhoto\\".$id.'.jpg');
        return $this->json("assets\\eventPhoto\\".'default.jpg');
    }
}