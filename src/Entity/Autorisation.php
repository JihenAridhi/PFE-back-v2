<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\AutorisationRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM ;

#[ORM\Entity(repositoryClass : AutorisationRepository::class)]
#[ApiResource]
class Autorisation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id;

    #[ORM\Column(length:255)]
    private ?string $autorisation;

    #[ORM\ManyToMany(targetEntity: Person::class, inversedBy: 'autorisations')]
    private int $person;

    /**
     * @param string|null $autorisation
     * @param Collection $person
     */
    public function __construct(?string $autorisation, Collection $person)
    {
        $this->autorisation = $autorisation;
        $this->person = $person;
    }

    public function __construct1($person = null)
    {
        $this->person = $person;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId (int $id) :self
    {
        $this->id = $id;
        return $this;
    }

    public function getAutorisation (): ?int
    {
        return $this->autorisation;
    }
    public function setAutorisation (string $autorisation): self
    {
        $this->autorisation=$autorisation;
        return $this;
    }



}