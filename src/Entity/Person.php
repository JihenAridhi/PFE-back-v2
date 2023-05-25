<?php

namespace App\Entity;

use App\Repository\PersonRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Ignore;

#[ORM\Entity(repositoryClass : PersonRepository::class)]
class Person
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    //#[ORM\OneToMany(targetEntity: PersonArticle::class, mappedBy: 'person_id')]
    private ?int $id;

    #[ORM\Column(length: 255)]
    private ?string $firstName;

    #[ORM\Column(length: 255)]
    private ?string $lastName;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $email;

    #[ORM\Column(length: 255)]
    private ?string $password;

    #[ORM\Column(length: 255 , nullable: true)]
    private ?string $profession;

    #[ORM\Column(length: 255 , nullable: true)]
    private ?string $team;

    #[ORM\Column]
    private ?bool $status;

    #[ORM\ManyToMany(targetEntity: Article::class, inversedBy: 'authors')]
    private Collection $article;

    #[ORM\ManyToMany(targetEntity: Autorisation::class, mappedBy: 'person')]
    private Collection $autorisations;

    #[ORM\ManyToMany(targetEntity: Theme::class, mappedBy: 'person')]
    private Collection $themes;

    #[ORM\Transient]
    private ?string $photo;

    public function __construct(?string $firstName, ?string $lastName, ?string $email, ?string $password)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->password = $password;
        $this->profession = null;
        $this->team = null;
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

    /**
     * @return string|null
     */
    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    /**
     * @param string|null $photo
     */
    public function setPhoto(?string $photo): void
    {
        $this->photo = $photo;
    }

    /**
     * @return Collection
     */
    public function getThemes(): Collection
    {
        return $this->themes;
    }

    /**
     * @param Collection $themes
     */
    public function setThemes(Collection $themes): void
    {
        $this->themes = $themes;
    }
}