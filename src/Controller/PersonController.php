<?php

namespace App\Controller;

use App\Entity\Person;
use App\Entity\Theme;
use App\Repository\PersonRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PersonController extends AbstractController
{
    private ManagerRegistry $managerRegistry;
    private PersonRepository $repo;
    private ObjectManager $objectManager;
    private $mailer;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
        $this->repo = $this->managerRegistry->getRepository(Person::class);
        $this->objectManager = $this->managerRegistry->getManager();
        $this->mailer = new Mailer(Transport::fromDsn('gmail+smtp://smartlab081@gmail.com:yayrebjziazjhxic@default'));
    }

    #[Route('/person/getAll')]
    public function getAll(): Response
    {
        $personList = $this->repo->findAll();
        foreach ($personList as $person)
            $person->setPhoto($this->getPhoto($person->getId()));
        return $this->json($personList);
    }
    
    #[Route('/person/getByFullName/{firstName}/{lastName}')]
    public function getByFullName(string $firstName, string $lastName): Response
    {
        $person = $this->repo->findOneBy(['fullName' => ($firstName.' '.$lastName)]);
        if ($person) {
            $person->setPhoto($this->getPhoto($person->getId()));
            return $this->json($person);
        }

        return $this->json(['error' => 'Person not found'], Response::HTTP_NOT_FOUND);
    }

    #[Route('/person/getAll/status/{status}')]
    public function getAllByStatus(bool $status): Response
    {
        $personList = $this->repo->findBy(['status'=>$status, 'coAuthor'=>false]);
        foreach ($personList as $person)
            $person->setPhoto($this->getPhoto($person->getId()));
        return $this->json($personList);
    }

    #[Route('/person/get/{id}')]
    public function get(int $id): Response
    {
        $person = $this->repo->find($id);
        $person->setPhoto($this->getPhoto($id));
        return $this->json($person);
    }

    #[Route('/person/getByEmail/{email}')]
    public function getByEmail(string $email): Response
    {
        $person = $this->repo->findOneBy(['email'=>$email]);
        $person->setPhoto($this->getPhoto($person->getId()));
        return $this->json($person);
    }

    #[Route('/person/login')]
    public function login(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $person = $this->repo->findOneBy(['email'=>$data['email']]);
        if ($person){
            $person->setPhoto($this->getPhoto($person->getId()));
            if (password_verify($data['password'], $person->getPassword()))
                $person->setPassword($data['password']);
            return $this->json($person);
        }
        return $this->json(null);
    }

    #[Route('/person/getEmail/{email}')]
    public function getEmail(string $email): Response
    {
        $person = $this->repo->createQueryBuilder('p')
            ->select('p.email')
            ->where('p.email=:email')
            ->setParameter('email', $email)
            ->getQuery()
            ->getOneOrNullResult();
        return $this->json($person);
    }

    #[Route('/person/add')]
    public function add(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        $person = new Person($data['fullName'], $data['email'], password_hash($data['password'],PASSWORD_DEFAULT));

        $person->setBio($data['bio']);
        $person->setDblp($data['dblp']);
        $person->setOrcid($data['orcid']);
        $person->setLinkedin($data['linkedin']);
        $person->setResearchGate($data['researchGate']);
        $person->setScholar($data['scholar']);
        $person->setPhone($data['phone']);
        $person->setCoAuthor(false);

        $this->objectManager->persist($person);
        $this->objectManager->flush();

        return$this->json($person->getId());
    }

    #[Route('/person/update')]
    public function update(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        $person = $this->repo->find($data['id']);
        $person->setFullName($data['fullName']);
        $person->setEmail($data['email']);
        $person->setPassword(password_hash($data['password'], PASSWORD_DEFAULT));
        $person->setBio($data['bio']);
        $person->setDblp($data['dblp']);
        $person->setOrcid($data['orcid']);
        $person->setLinkedin($data['linkedin']);
        $person->setResearchGate($data['researchGate']);
        $person->setScholar($data['scholar']);
        $person->setPhone($data['phone']);
        $this->setThemes($data['themes'], $person);

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
        $server = 'C:\Users\ARIDHI\Desktop\PFE - Copy\PFE-front\src\\';
        $file = $request->files->get('file');
        $fileName = $file->getClientOriginalName();

        try {$file->move($server.'assets\userPhoto\\', $fileName);}
        catch (FileException $e) {}

        return $this->json('assets\userPhoto\\'.$fileName);
    }

    #[Route('photo/user/get/{id}')]
    public function getPhoto(int $id)
    {
        $server = 'C:\Users\ARIDHI\Desktop\PFE - Copy\PFE-front\src\\';
        $path = $server."assets\userPhoto\\";
        if (file_exists($path.$id.'.jpg'))
            return /*$this->json(*/"assets/userPhoto/".$id.'.jpg'/*)*/;
        return /*$this->json(*/'assets/userPhoto/default.jpg'/*)*/;
    }

    #[Route('/person/sendMail')]
    public function sendEmail(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $email = (new Email())
            ->from('smartlab081@gmail.com')
            ->to($data['to'])
            ->subject($data['subject'])
            ->html($data['html']);

            $this->mailer->send($email);
            return $this->json("sent");
    }

    public function setThemes(array $themes, Person $person): Response
    {
        foreach ($person->getThemes() as $theme)
        {
            $theme->getPerson()->removeElement($person);
            $person->getThemes()->removeElement($theme);
            $this->objectManager->persist($theme);
        }

        foreach ($themes as $theme)
        {
            $theme = $this->objectManager->getRepository(Theme::class)->find($theme['id']);
            $theme->getPerson()->add($person);
            $person->getThemes()->add($theme);
            $this->objectManager->persist($theme);
        }
        $this->objectManager->persist($person);
        $this->objectManager->flush();
        return $this->json($person);
    }

    #[Route('cv')]
    public function uploadCV(Request $request): Response
    {
        $server = 'C:\Users\ARIDHI\Desktop\PFE - Copy\PFE-front\src\\';
        $file = $request->files->get('file');
        $fileName = $file->getClientOriginalName();

        try {$file->move($server.'assets\CV\\', $fileName);}
        catch (FileException $e) {}

        return $this->json('assets\CV\\'.$fileName);
    }
}