<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\ArticlePerson;
use App\Entity\Person;
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
        $articles = $this->repo->findAll();
        foreach ($articles as $article)
            $article->setAuthors();
        return $this->json($articles);
    }

    #[Route('/article/get/{id}')]
    public function get(int $id): Response
    {
        $article = $this->repo->find($id);
        $article->setAuthors();
        return $this->json($article);
    }

    #[Route('/article/getAll/{id}')]
    public function getPersonArticles(int $id): Response
    {
        $articles = $this->repo->createQueryBuilder('a')
            ->leftJoin('a.articleAuthor', 'p')
            ->andWhere('p.author=:id')
            ->setParameter(':id', $id)
            ->orderBy('a.year', 'DESC')
            ->getQuery()
            ->getResult();
        foreach ($articles as $article)
            $article->setAuthors();
        return $this->json($articles);
    }

    #[Route('/article/add')]
    public function add(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        $article = new Article($data['title'], $data['type'], $data['year'], $data['description']);
        $this->setInfo($article, $data);

        $this->objectManager->persist($article);
        $this->objectManager->flush();

        $this->setAuthors($article, $data['authors']);

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
        $article->setDescription($data['description']);
        $this->setInfo($article, $data);

        $this->objectManager->persist($article);
        $this->objectManager->flush();

        $this->setAuthors($article, $data['authors']);

        return $this->json($article);
    }

    public function setInfo(Article $article, $data)
    {
        $article->setName($data['name']);
        $article->setBibtex($data['bibtex']);
        if ($data['type']=='Journal')
        {
            $article->setVolume($data['volume']);
            $article->setNumero($data['numero']);
            $article->setFirstPage($data['firstPage']);
            $article->setLastPage($data['lastPage']);
        }
        if ($data['type']=='Conference')
        {
            $article->setMonth($data['month']);
            $article->setLocation($data['location']);
        }
        if ($data['type']=='Book')
            $article->setEditor($data['editor']);
        if ($data['type']=='Thesis')
            $article->setInstitute($data['institute']);
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

    public function setAuthors(Article $article, array $authors): Response
    {
        foreach ($article->getArticleAuthor() as $association) {
            $author = $association->getAuthor();
            $author->getArticleAuthor()->removeElement($association);
            $article->getArticleAuthor()->removeElement($association);
            $this->objectManager->remove($association);
        }

        for ($i=0; $i<count($authors); $i++)
        {
            if(!$authors[$i]['id']) {
                $author = new Person($authors[$i]['fullName'], '', '');
                $author->setCoAuthor(true);
                $author->setStatus(null);
                $this->objectManager->persist($author);
            }
            else
                $author = $this->managerRegistry->getRepository(Person::class)->find($authors[$i]['id']);

            $association = new ArticlePerson($article, $author);
            $this->objectManager->persist($association);
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
        $oldFile = glob($server . 'assets\aricleFile\\'.pathinfo($fileName, PATHINFO_FILENAME).'.*');
        if (!empty($oldFile))
            unlink($oldFile[0]);
        try {$file->move($server . 'assets\aricleFile\\', $fileName);}
        catch (FileException $e) {}

        return $this->json('assets\aricleFile\\' . $fileName);
    }

    #[Route('article/file/get/{id}')]
    public function getArticle(int $id)
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