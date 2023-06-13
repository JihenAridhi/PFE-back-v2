<?php

namespace App\Controller;

use App\Entity\Event;
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
    {
        $eventList = $this->repo->findAll();
        foreach ($eventList as $event)
            $event->setPhoto($this->getPhoto($event->getId()));
        return $this->json($eventList);
    }

    #[Route('/event/get/{id}')]
    public function get(int $id): Response
    {
        $event = $this->repo->find($id);
        $event->setPhoto($this->getPhoto($id));
        return $this->json($event);
    }

    #[Route('/event/add')]
    public function add(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        $event = new Event($data['title'], $data['date'], $data['location'], $data['organiser'], $data['description']);

        $this->objectManager->persist($event);

        $this->objectManager->flush();

        return $this->json($event->getId());
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

    #[Route('photo/event/{id}')]
    public function upload(Request $request, int $id): Response
    {
        $server = 'C:\Users\ARIDHI\Desktop\PFE - Copy\PFE-front\src\\';
        $folder = $server . 'assets\eventPhoto\\' . $id;

        if (!file_exists($folder)) {
            mkdir($folder, 0777, true);
        }

        $files = $request->files->get('files');

        foreach ($files as $file) {
            $fileName = $file->getClientOriginalName();
            try {$file->move($folder, $fileName);}
            catch (FileException $e) {}
        }

        return $this->json('');
    }

    public function getPhoto(int $id): string
    {
        $server = 'C:\Users\ARIDHI\Desktop\PFE - Copy\PFE-front\src\\';
        $path = $server."assets\\eventPhoto\\";
        if (file_exists($path.$id.'.jpg'))
            return "assets\\eventPhoto\\".$id.'.jpg';
        return 'assets\\eventPhoto\\default.jpg';
    }
}