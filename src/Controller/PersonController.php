<?php

namespace App\Controller;

use App\Entity\Person;
use App\Repository\PersonRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
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

    #[Route('/person/getAll/profession/{profession}')]
    public function getAllByProfession(string $profession): Response
    {return $this->json($this->repo->findBy(['profession' => $profession]));}

    #[Route('/person/getAll/status/{status}')]
    public function getAllByStatus(bool $status): Response
    {return $this->json($this->repo->findBy(['status'=>$status]));}

    #[Route('/person/get/{id}')]
    public function get(int $id): Response
    {return $this->json($this->repo->find($id));}

    #[Route('/person/getByEmail/{email}')]
    public function getByEmail(string $email): Response
    {return $this->json($this->repo->findOneBy(['email'=>$email]));}

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

    #[Route('/person/accept/{id}')]
    public function accept(int $id): Response
    {
        $person = $this->repo->find($id);
        $person->setStatus(true);
        $this->objectManager->persist($person);
        $this->objectManager->flush();
        return $this->json($person);
    }

    #[Route('/person/delete/{id}')]
    public function delete(int $id): Response
    {
        $this->objectManager->remove($this->repo->find($id));
        $this->objectManager->flush();
        return $this->json('success!!');
    }

    #[Route('photo/user')]
    public function upload(Request $request): Response
    {
        $server = 'C:\Users\ARIDHI\Desktop\PFE\PFE-front\src\\';
        $file = $request->files->get('file');
        $fileName = $file->getClientOriginalName();

        try {$file->move($server.'assets\userPhoto\\', $fileName);}
        catch (FileException $e) {}

        return $this->json('C:\Users\ARIDHI\Desktop\PFE\PFE-front\src\assets\userPhoto\\'.$fileName);
    }

    #[Route('photo/user/get/{id}')]
    public function getPhoto(int $id)
    {
        $server = 'C:\Users\ARIDHI\Desktop\PFE\PFE-front\src\\';
        $path = $server."assets\userPhoto\\";
        if (file_exists($path.$id.'.jpg'))
            return $this->json($id.'.jpg');
        return $this->json('default.jpg');
    }
}
