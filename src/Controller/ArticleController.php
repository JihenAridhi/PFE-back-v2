<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Person;
use App\Entity\PersonArticle;
use App\Repository\ArticleRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
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
    {
        return $this->json($this->repo->findAll());
    }

    #[Route('/article/get/{id}')]
    public function get(int $id): Response
    {
        return $this->json($this->repo->find($id));
    }

    #[Route('/article/getAll/{id}')]
    public function getPersonArticles(int $id): Response
    {
        $articles = $this->repo->createQueryBuilder('a')
            ->leftJoin('a.authors', 'p')
            ->andWhere('p.id=:id')
            ->setParameter(':id', $id)
            ->orderBy('a.id', 'DESC')
            ->getQuery()
            ->getResult();
        return $this->json($articles);
    }

    #[Route('/article/add')]
    public function add(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        $article = new Article($data['title'], $data['type'], $data['journal'], $data['date'], $data['firstPage'], $data['lastPage']/*, $data['editor']*/, $data['description'], $data['url']);

        $this->setAuthors($article->getId(), $data['authors']);

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
        $article->setFirstPage($data['firstPage']);
        $article->setLastPage($data['lastPage']);
        $article->setDescription($data['description']);
        $article->setUrl($data['url']);
        $article->setJournal($data['journal']);
        $this->setAuthors($article->getId(), $data['authors']);

        $this->objectManager->persist($article);
        $this->objectManager->flush();

        return $this->json($article->getAuthors());
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

    public function setAuthors(int $id, array $authors): Response
    {
        $article = $this->repo->find($id);
        /*foreach ($article->getAuthors() as $author)
            $author->getArticle()->removeElement($article);
        $article->getAuthors()->clear();
        for ($i = 0; $i < count($authors); $i++)
        {
            $author = $this->managerRegistry->getRepository(Person::class)->find($authors[$i]);
            $author->getArticle()->add($article);
            $article->getAuthorsOrder()[] = $author->getId();
            $this->objectManager->persist($author);
        }*/
        foreach ($article->getAuthors() as $author) {
            if (!in_array($author->getId(), $authors)) {
                $article->getAuthors()->removeElement($author);
                $author->getArticle()->removeElement($article);
            }
        }
        for ($i = 0; $i < count($authors); $i++) {
            $author = $this->managerRegistry->getRepository(Person::class)->find($authors[$i]);
            if (!$article->getAuthors()->contains($author)) {
                $author->getArticle()->add($article);
                $this->objectManager->persist($author);
            }
        }
        $this->objectManager->flush();
        return $this->json($article);
    }

    #[Route('article/file')]
    public function upload(Request $request): Response
    {
        $server = 'C:\Users\ARIDHI\Desktop\PFE\PFE-front\src\\';
        $file = $request->files->get('file');
        $fileName = $file->getClientOriginalName();
        try {
            $file->move($server . 'assets\aricleFile\\', $fileName);
        } catch (FileException $e) {
        }

        return $this->json('assets\aricleFile\\' . $fileName);
    }

    #[Route('article/file/get/{id}')]
    public function getPhoto(int $id)
    {
        $server = 'C:\Users\ARIDHI\Desktop\PFE\PFE-front\src\\';
        $path = $server . "assets\aricleFile\\";
        $finder = new Finder();
        $finder->in($path)->name($id . '.*');
        foreach ($finder as $file)
            $filename = $file->getFilename();
        return $this->json($path . $filename);
    }
}