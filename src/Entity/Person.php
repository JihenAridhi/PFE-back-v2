<?php

namespace App\Entity;

use App\Repository\PersonRepository;
use Doctrine\Common\Collections\ArrayCollection;
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
    private ?int $id;

    #[ORM\Column(length: 255)]
    private ?string $fullName;

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

    #[ORM\Column]
    private ?bool $coAuthor;

    /*#[ORM\ManyToMany(targetEntity: Article::class, inversedBy: 'authors')]
    private Collection $article;*/

    #[ORM\ManyToMany(targetEntity: Autorisation::class, mappedBy: 'person')]
    private Collection $autorisations;

    #[ORM\ManyToMany(targetEntity: Theme::class, mappedBy: 'person')]
    private Collection $themes;

    #[ORM\Transient]
    private ?string $photo;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: ArticlePerson::class)]
    private Collection $articleAuthor;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $phone = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $bio = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $researchGate = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $orcid = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $scholar = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $linkedin = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $dblp = null;

    public function __construct(?string $fullName, ?string $email, ?string $password)
    {
        $this->fullName = $fullName;
        $this->email = $email;
        $this->password = $password;
        $this->profession = null;
        $this->team = null;
        $this->status = false;
        $this->articleAuthor = new ArrayCollection();
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

    /**
     * @return Collection<int, ArticlePerson>
     * @Ignore
     */
    public function getArticleAuthor(): Collection
    {
        return $this->articleAuthor;
    }

    public function addArticlePerson(ArticlePerson $articlePerson): self
    {
        if (!$this->articleAuthor->contains($articlePerson)) {
            $this->articleAuthor->add($articlePerson);
            $articlePerson->setAuthor($this);
        }

        return $this;
    }

    public function removeArticlePerson(ArticlePerson $articlePerson): self
    {
        if ($this->articleAuthor->removeElement($articlePerson)) {
            // set the owning side to null (unless already changed)
            if ($articlePerson->getAuthor() === $this) {
                $articlePerson->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getCoAuthor(): ?bool
    {
        return $this->coAuthor;
    }

    /**
     * @param bool|null $coAuthor
     */
    public function setCoAuthor(?bool $coAuthor): void
    {
        $this->coAuthor = $coAuthor;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getBio(): ?string
    {
        return $this->bio;
    }

    public function setBio(?string $bio): self
    {
        $this->bio = $bio;

        return $this;
    }

    public function getResearchGate(): ?string
    {
        return $this->researchGate;
    }

    public function setResearchGate(?string $researchGate): self
    {
        $this->researchGate = $researchGate;

        return $this;
    }

    public function getOrcid(): ?string
    {
        return $this->orcid;
    }

    public function setOrcid(?string $orcid): self
    {
        $this->orcid = $orcid;

        return $this;
    }

    public function getScholar(): ?string
    {
        return $this->scholar;
    }

    public function setScholar(?string $scholar): self
    {
        $this->scholar = $scholar;

        return $this;
    }

    public function getLinkedin(): ?string
    {
        return $this->linkedin;
    }

    public function setLinkedin(?string $linkedin): self
    {
        $this->linkedin = $linkedin;

        return $this;
    }

    public function getDblp(): ?string
    {
        return $this->dblp;
    }

    public function setDblp(?string $dblp): self
    {
        $this->dblp = $dblp;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    /**
     * @param string|null $fullName
     */
    public function setFullName(?string $fullName): void
    {
        $this->fullName = $fullName;
    }

}