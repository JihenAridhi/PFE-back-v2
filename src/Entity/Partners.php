<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\PartnersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Ignore;
#[ORM\Entity(repositoryClass : PartnersRepository::class)]
//#[ApiResource]
class Partners
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id ;

    #[ORM\Column(length: 255)]
    private ?string $name ;

    #[ORM\Column(length: 255)]
    private ?string $type ;

    #[ORM\Column(Types::TEXT)]
    private ?string $description ;

    #[ORM\Column(length: 255)]
    private ?string $urlPage ;

    #[ORM\Transient]
    private ?string $photo;

    #[ORM\Column]
    private ?bool $coPartner;

    #[ORM\OneToMany(mappedBy: 'partner', targetEntity: ProjectPartners::class)]
    private Collection $projectPartner;
    /**
     * @param string|null $name
     * @param string|null $type
     * @param string|null $description
     * @param string|null $urlPage
     */
    public function __construct(?string $name, ?string $type, ?string $description, ?string $urlPage)
    {
        $this->name = $name;
        $this->type = $type;
        $this->description = $description;
        $this->urlPage = $urlPage;
        $this->projectPartner = new ArrayCollection();
    }

    /**
     * @return bool|null
     */
    public function getCoPartner(): ?bool
    {
        return $this->coPartner;
    }

    /**
     * @param bool|null $coPartner
     */
    public function setCoPartner(?bool $coPartner): void
    {
        $this->coPartner = $coPartner;
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
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
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
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string|null
     */
    public function getUrlPage(): ?string
    {
        return $this->urlPage;
    }

    /**
     * @param string|null $urlPage
     */
    public function setUrlPage(?string $urlPage): void
    {
        $this->urlPage = $urlPage;
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
     * @return Collection<int, ProjectPartners>
     * @Ignore
     */
    public function getProjectPartner(): Collection
    {
        return $this->projectPartner;
    }

    public function addProjectPartner(ProjectPartners $projectPartner): self
    {
        if (!$this->projectPartner->contains($projectPartner)) {
            $this->projectPartner->add($projectPartner);
            $projectPartner->setPartner($this);
        }

        return $this;
    }

    public function removeProjectPartner(ProjectPartners $projectPartner): self
    {
        if ($this->projectPartner->removeElement($projectPartner)) {
            // set the owning side to null (unless already changed)
            if ($projectPartner->getPartner() === $this) {
                $projectPartner->setPartner(null);
            }
        }

        return $this;
    }


}
