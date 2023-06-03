<?php

namespace App\Entity;

use App\Repository\ArticlePersonRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Ignore;

#[ORM\Entity(repositoryClass: ArticlePersonRepository::class)]
class ArticlePerson
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'articleAuthor')]
    private ?Article $article = null;

    #[ORM\ManyToOne(inversedBy: 'articleAuthor')]
    private ?Person $author = null;


    /**
     * @param Article|null $article
     * @param Person|null $author
     */
    public function __construct(?Article $article, ?Person $author)
    {
        $this->article = $article;
        $this->author = $author;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return Article|null
     */
    public function getArticle(): ?Article
    {
        return $this->article;
    }

    /**
     * @param Article|null $article
     */
    public function setArticle(?Article $article): void
    {
        $this->article = $article;
    }

    /**
     * @return Person|null
     */
    public function getAuthor(): ?Person
    {
        return $this->author;
    }

    /**
     * @param Person|null $author
     */
    public function setAuthor(?Person $author): void
    {
        $this->author = $author;
    }
}
