<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Person;
use App\Repository\ArticleRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    private ManagerRegistry $managerRegistry;
    private ArticleRepository $repo;
    private ObjectManager $objectManager;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
        $this->repo = $this->managerRegistry->getRepository(Article::class);
        $this->objectManager = $this->managerRegistry->getManager();
    }

    #[Route('/article/getAll')]
    public function getAll(): Response
    {return $this->json($this->repo->findAll());}

    #[Route('/article/get/{id}')]
    public function get(int $id): Response
    {return $this->json($this->repo->find($id));}

    #[Route('/article/add')]
    public function add(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        $article = new Article($data['title'], $data['type'], $data['date'], $data['firstPage'], $data['lastPage'], $data['editor']);

        $article->setAuthors($data['authors']);
        for ( $i=0; $i<$data['authors'].length(); $i++)
        {
            $author = $this->managerRegistry->getRepository(Person::class)->find($data['authors'][$i]->getId());
            $author->getArticle()->add($article);
            $article->getAuthors()->add($author);

            $this->objectManager->persist($author);
        }

        $this->objectManager->persist($article);

        $this->objectManager->flush();

        return $this->json('success');
    }

    #[Route('/article/update')]
    public function update(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        $article = $this->repo->find($data['id']);
        $article->setAuthors($data['authors']);
        $article->setTitle($data['title']);
        $article->setType($data['type']);
        $article->setDate($data['date']);
        $article->setFirstPage($data['firstPage']);
        $article->setLastPage($data['lastPage']);
        $article->setEditor($data['editor']);

        return $this->json($article);
    }

    #[Route('/article/delete/{id}')]
    public function delete(int $id): Response
    {
        $this->repo->remove($id);
        return $this->json('success !!');
    }
}
