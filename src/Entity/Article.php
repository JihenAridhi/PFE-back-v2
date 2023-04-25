<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use DateTime;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM ;
use Symfony\Component\Serializer\Annotation\Ignore;

#[ORM\Entity(repositoryClass : ArticleRepository::class)]

class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id;

    #[ORM\Column(length: 255)]
    private ?string $title;

    #[ORM\Column(length: 255 , nullable: true)]
    private ?string $type;

    #[ORM\Column(length: 255)]
    private ?string $journal;

    #[ORM\Column]
    private ?int $firstPage;

    #[ORM\Column]
    private ?int $lastPage;

    #[ORM\Column(nullable: true)]
    private ?string $editor;
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $DOI;

    #[ORM\Column(length: 255)]
    private ?string $url;

    #[ORM\ManyToMany(targetEntity: Person::class, mappedBy: 'article')]
    private Collection $authors;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private DateTime $date;

    /**
     * @param string|null $title
     * @param string|null $type
     * @param string|null $journal
     * @param DateTime|null $date
     * @param int|null $firstPage
     * @param int|null $lastPage
     * @param string|null $editor
     * @param string|null $description
     * @param string|null $DOI
     */
    public function __construct(?string $title, ?string $type, ?string $journal, ?string $date, ?int $firstPage, ?int $lastPage/*, ?string $editor*/, ?string $description, ?string $url/*, ?string $DOI*/)
    {
        $this->title = $title;
        $this->type = $type;
        $this->journal = $journal;
        $this->date = new DateTime($date);
        $this->firstPage = $firstPage;
        $this->lastPage = $lastPage;
        //$this->editor = $editor;
        $this->description = $description;
        $this->url = $url;
        //$this->DOI = $DOI;
    }

    /**
     * @return string|null
     */
    public function getJournal(): ?string
    {
        return $this->journal;
    }

    /**
     * @param string|null $journal
     */
    public function setJournal(?string $journal): void
    {
        $this->journal = $journal;
    }



    /**
     * @return string|null
     */
    public function getDOI(): ?string
    {
        return $this->DOI;
    }

    /**
     * @param string|null $DOI
     */
    public function setDOI(?string $DOI): void
    {
        $this->DOI = $DOI;
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
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string|null $title
     */
    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string|null $type
     */
    public function setType(?string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return int|null
     */
    public function getFirstPage(): ?int
    {
        return $this->firstPage;
    }

    /**
     * @param int|null $firstPage
     */
    public function setFirstPage(?int $firstPage): void
    {
        $this->firstPage = $firstPage;
    }

    /**
     * @return int|null
     */
    public function getLastPage(): ?int
    {
        return $this->lastPage;
    }

    /**
     * @param int|null $lastPage
     */
    public function setLastPage(?int $lastPage): void
    {
        $this->lastPage = $lastPage;
    }

    /**
     * @return string|null
     */
    public function getEditor(): ?string
    {
        return $this->editor;
    }

    /**
     * @param string|null $editor
     */
    public function setEditor(?string $editor): void
    {
        $this->editor = $editor;
    }

    /**
     * @return string|null
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @param string|null $url
     */
    public function setUrl(?string $url): void
    {
        $this->url = $url;
    }

    /**
     * @return Collection
     * @Ignore
     */
    public function getAuthors()
    {
        return $this->authors;
    }

    /**
     * @param Collection $authors
     */
    public function setAuthors(Collection $authors): void
    {
        $this->authors = $authors;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDate(): DateTime
    {
        return $this->date;
    }

    public function setDate(string $date): self
    {
        $this->date = new DateTime($date);

        return $this;
    }

    

}