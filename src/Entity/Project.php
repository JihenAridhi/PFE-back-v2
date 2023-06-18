<?php

namespace App\Entity;

use App\Repository\ProjectRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Ignore;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
class Project
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?DateTime $date ;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Transient]
    private ?string $photo;

    #[ORM\OneToMany(mappedBy: 'project', targetEntity: ProjectPartners::class)]
    private Collection $projectPartner;


    #[ORM\Transient]
    private Collection $partners;

    /**
     * @param string|null $title
     * @param DateTime|null $date
     * @param string|null $description
     * @param string|null $type
     */
    public function __construct(?string $title, ?string $date, ?string $description, ?string $type)
    {
        $this->title = $title;
        $this->date = new DateTime($date);
        $this->description = $description;
        $this->type= $type;
    }

    public function getId(): ?int
    {
        return $this->id;
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
    public function setDate(?string $date): void
    {
        $this->date = new DateTime($date);
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
    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
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
     * @param Collection $partners
     */
    public function setPartners(): void
    {
        $this->partners = new ArrayCollection();
        for($i=0; $i<count($this->projectPartner); $i++)
            $this->partners[$i]=$this->projectPartner[$i]->getPartner();
    }

    /**
     * @return Collection
     */
    public function getPartners(): Collection
    {
        return $this->partners;
    }
    /**
     * @return Collection<int, ProjectPartners>
     * @Ignore
     */
    public function getProjectPartner(): Collection
    {
        return $this->projectPartner;
    }
    public function addProjectPartner(ProjectPartners $projectPartner): self
    {
        if (!$this->projectPartner>contains($projectPartner)) {
            $this->projectPartner->add($projectPartner);
            $projectPartner->setProject($this);
        }

        return $this;
    }

    public function removeProjectPartner(ProjectPartners $projectPartner): self
    {
        if ($this->projectPartner->removeElement($projectPartner)) {
            // set the owning side to null (unless already changed)
            if ($projectPartner->getProject() === $this) {
                $projectPartner->setProject(null);
            }
        }

        return $this;
    }


}
