<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use DateTime;
use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM ;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity(repositoryClass : ArticleRepository::class)]
#[ApiResource]

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

    #[ORM\Column (type: Types::DATE_MUTABLE)]
    private ?\DateTime $date;

    #[ORM\Column]
    private ?int $firstPage;

    #[ORM\Column]
    private ?int $lastPage;

    #[ORM\Column]
    private ?string $editor;

    #[ORM\ManyToMany(targetEntity: Person::class, mappedBy: 'article')]
    private Collection $authors;

    /**
     * @param string|null $title
     * @param string|null $type
     * @param DateTime|null $date
     * @param int|null $firstPage
     * @param int|null $lastPage
     * @param string|null $editor
     */
    public function __construct(?string $title, ?string $type, ?DateTime $date, ?int $firstPage, ?int $lastPage, ?string $editor)
    {
        $this->title = $title;
        $this->type = $type;
        $this->date = $date;
        $this->firstPage = $firstPage;
        $this->lastPage = $lastPage;
        $this->editor = $editor;
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
     * @return DateTime|null
     */
    public function getDate(): ?DateTime
    {
        return $this->date;
    }

    /**
     * @param DateTime|null $date
     */
    public function setDate(?DateTime $date): void
    {
        $this->date = $date;
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



}