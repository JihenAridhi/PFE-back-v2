<?php

namespace App\Entity;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\NewsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM ;



#[ORM\Entity(repositoryClass : NewsRepository::class)]
//#[ApiResource]
class News extends NewsRepository
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id ;

    #[ORM\Column(length: 255)]
    private ?string $name ;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $date ;

    #[ORM\Column(length: 255)]
    private ?string $description ;

    #[ORM\Column(type: Types::BLOB, nullable: true)]
    private $photo = null;

    /**
     * @param string|null $name
     * @param \DateTime|null $date
     * @param string|null $description
     */
    public function __construct(?string $name, ?\DateTime $date, ?string $description)
    {
        $this->name = $name;
        $this->date = $date;
        $this->description = $description;
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
    public function getName(): ?string
    {
        return $this->name;
    }
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    /**
     * @param \DateTime|null $date
     */
    public function setDate(?\DateTime $date): void
    {
        $this->date = $date;
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

}