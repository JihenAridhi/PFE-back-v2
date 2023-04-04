<?php

namespace App\Entity;
use App\Repository\NewsRepository;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM ;



#[ORM\Entity(repositoryClass : NewsRepository::class)]
class News
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id ;

    #[ORM\Column(length: 255)]
    private ?string $title ;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?DateTime $date ;

    #[ORM\Column(length: 255)]
    private ?string $description ;

    #[ORM\Column(type: Types::BLOB, nullable: true)]
    private $photo = null;

    /**
     * @param string|null $title
     * @param DateTime|null $date
     * @param string|null $description
     */
    public function __construct(?string $title, ?DateTime $date, ?string $description, $photo)
    {
        $this->title = $title;
        $this->date = $date;
        $this->description = $description;
        $this->photo = $photo;
    }


    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
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
     * @param string|null $title
     */
    public function setTitle(?string $title): void
    {
        $this->title = $title;
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

    /**
     * @return null
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * @param null $photo
     */
    public function setPhoto($photo): void
    {
        $this->photo = $photo;
    }



}