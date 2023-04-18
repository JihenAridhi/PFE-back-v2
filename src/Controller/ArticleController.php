<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Person;
use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use phpDocumentor\Reflection\DocBlock\Tags\Author;
use PhpParser\Node\Expr\Array_;
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

    #[Route('/article/getAll/{id}')]
    public function getPersonArticles(int $id): Response
    {
        $articles = $this->repo->createQueryBuilder('a')
            ->leftJoin('a.authors','p')
            ->andWhere('p.id=:id')
            ->setParameter(':id', $id)
            ->getQuery()
            ->getResult();
        return $this->json($articles);
    }

    #[Route('/article/add')]
    public function add(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        $article = new Article($data['title'], $data['type'], $data['journal'], $data['year'], $data['firstPage'], $data['lastPage'], $data['editor'], $data['description'], $data['url']);

        $this->objectManager->persist($article);

        $this->objectManager->flush();

        return $this->json($article);
    }

    #[Route('/article/update')]
    public function update(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        $article = $this->repo->find($data['id']);
        $article->setTitle($data['title']);
        $article->setType($data['type']);
        $article->setYear($data['year']);
        $article->setFirstPage($data['firstPage']);
        $article->setLastPage($data['lastPage']);
        $article->setEditor($data['editor']);
        $article->setDescription($data['description']);
        $article->setUrl($data['url']);
        //$article->setDOI($data['DOI']);
        $article->setJournal($data['journal']);

        $this->objectManager->persist($article);
        $this->objectManager->flush();

        return $this->json($article);
    }

    #[Route('/article/delete/{id}')]
    public function delete(int $id): Response
    {
        $this->repo->remove($this->repo->find($id));
        $this->objectManager->flush();
        return $this->json('success !!');
    }

    #[Route('/article/{id}/getAuthors')]
    public function getAuthors(int $id): Response
    {
        $article = $this->repo->find($id);
        return $this->json($article->getAuthors());
    }

    #[Route('/article/{id}/setAuthors')]
    public function setAuthors(int $id, Request $request): Response
    {
        $article = $this->repo->find($id);
        $data = json_decode($request->getContent(), true);

        foreach ($article->getAuthors() as $author) {
            if (!in_array($author->getId(), $data)) {
                $article->getAuthors()->removeElement($author);
                //$this->objectManager->remove($author);
            }
        }

        for($i=0; $i<count($data); $i++)
        {
            $author = $this->managerRegistry->getRepository(Person::class)->find($data[$i]);
            if (!$article->getAuthors()->contains($author)) {
                $author->getArticle()->add($article);
                $this->objectManager->persist($author);
            }
        }

        $this->objectManager->flush();
        return $this->json($article->getAuthors());
    }
}