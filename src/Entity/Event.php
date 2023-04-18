<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\EventRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM ;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity(repositoryClass : EventRepository::class)]
//#[ApiResource]
class Event extends EventRepository
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id ;

    #[ORM\Column(length: 255)]
    private ?string $title ;

    #[ORM\Column (type: Types::DATE_MUTABLE)]
    private ?DateTime $date;

    #[ORM\Column(length: 255)]
    private ?string $location ;

    #[ORM\Column(length: 255)]
    private ?string $organiser;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description ;

    /**
     * @param string|null $title
     * @param DateTime|null $date
     * @param string|null $location
     * @param string|null $organiser
     * @param string|null $description
     */

    public function __construct(?string $title, ?string $date, ?string $location, ?string $organiser, ?string $description)
    {
        $this->title = $title;
        $this->date = new DateTime($date);
        $this->location = $location;
        $this->organiser = $organiser;
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
    public function getTitle(): ?string
    {
        return $this->title;
    }
    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
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

    public function getLocation(): ?string
    {
        return $this->location;
    }
    public function setLocation(string $location): self
    {
        $this->location = $location;
        return $this;
    }
    public function getOrganiser(): ?string
    {
        return $this->organiser;
    }
    public function setOrganiser(string $organiser): self
    {
        $this->organiser = $organiser;
        return $this;
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