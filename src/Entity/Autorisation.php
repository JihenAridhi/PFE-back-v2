<?php

namespace App\Entity;

use App\Repository\AutorisationRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM ;
use Symfony\Component\Serializer\Annotation\Ignore;

#[ORM\Entity(repositoryClass : AutorisationRepository::class)]
class Autorisation extends AutorisationRepository
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id;

    #[ORM\Column(length:255)]
    private ?string $autorisation;

    #[ORM\ManyToMany(targetEntity: Person::class, inversedBy: 'autorisations')]
    private Collection $person;

    /**
     * @param string|null $autorisation
     * @param Collection $person
     */
    public function __construct(?string $autorisation/*, Collection $person*/)
    {
        $this->autorisation = $autorisation;
        //$this->person = $person;
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
    public function getAutorisation(): ?string
    {
        return $this->autorisation;
    }

    /**
     * @param string|null $autorisation
     */
    public function setAutorisation(?string $autorisation): void
    {
        $this->autorisation = $autorisation;
    }

    /**
     * @return Collection
     * @Ignore
     */
    public function getPerson(): Collection
    {
        return $this->person;
    }

    /**
     * @param Collection $person
     */
    public function setPerson(Collection $person): void
    {
        $this->person = $person;
    }


}