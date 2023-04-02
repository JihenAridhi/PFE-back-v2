<?php

namespace App\Entity;

use App\Repository\PersonRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Ignore;

#[ORM\Entity(repositoryClass : PersonRepository::class)]
class Person //extends PersonRepository
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id;

    #[ORM\Column(length: 255)]
    private ?string $firstName;

    #[ORM\Column(length: 255)]
    private ?string $lastName;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $email;

    #[ORM\Column(length: 255)]
    private ?string $password;

    #[ORM\Column(type: Types::BLOB , nullable: true)]
    private $photo = null;

    #[ORM\Column(length: 255 , nullable: true)]
    private ?string $profession;

    #[ORM\Column(length: 255 , nullable: true)]
    private ?string $team;

    #[ORM\Column(length: 255 , nullable: true)]
    private ?string $interest;

    #[ORM\Column]
    private ?bool $status;

    #[ORM\ManyToMany(targetEntity: Article::class, inversedBy: 'authors')]
    private Collection $article;

    #[ORM\ManyToMany(targetEntity: Autorisation::class, mappedBy: 'person')]
    private Collection $autorisations;

    public function Person()
    {}

    public function __construct(?string $firstName, ?string $lastName, ?string $email, ?string $password)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->password = $password;
        $this->profession = null;
        $this->team = null;
        $this->interest = null;
        $this->status = false;
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
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param string|null $firstName
     */
    public function setFirstName(?string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string|null
     */
    public function getlastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @param string|null $lastName
     */
    public function setlastName(?string $lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     */
    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string|null $password
     */
    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return string|null
     */
    public function getProfession(): ?string
    {
        return $this->profession;
    }

    /**
     * @param string|null $profession
     */
    public function setProfession(?string $profession): void
    {
        $this->profession = $profession;
    }

    /**
     * @return string|null
     */
    public function getTeam(): ?string
    {
        return $this->team;
    }

    /**
     * @param string|null $team
     */
    public function setTeam(?string $team): void
    {
        $this->team = $team;
    }

    /**
     * @return string|null
     */
    public function getInterest(): ?string
    {
        return $this->interest;
    }

    /**
     * @param string|null $interest
     */
    public function setInterest(?string $interest): void
    {
        $this->interest = $interest;
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

    /**
     * @return bool|null
     */
    public function getStatus(): ?bool
    {
        return $this->status;
    }

    /**
     * @param bool|null $status
     */
    public function setStatus(?bool $status): void
    {
        $this->status = $status;
    }

    /**
     * @return Collection
     * @Ignore
     */
    public function getArticle(): Collection
    {
        return $this->article;
    }

    /**
     * @param Collection $article
     */
    public function setArticle(Collection $article): void
    {
        $this->article = $article;
    }

    /**
     * @return Collection
     * @Ignore
     */
    public function getAutorisations(): Collection
    {
        return $this->autorisations;
    }

    /**
     * @param Collection $autorisations
     */
    public function setAutorisations(Collection $autorisations): void
    {
        $this->autorisations = $autorisations;
    }



}