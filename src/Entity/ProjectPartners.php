<?php

namespace App\Entity;


use App\Repository\ProjectPartnersRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Ignore;


#[ORM\Entity(repositoryClass: ProjectPartnersRepository::class)]

class ProjectPartners
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'projectPartner')]
    private ?Project $project = null;

    #[ORM\ManyToOne(inversedBy: 'projectPartner')]
    private ?Partners $partner = null;

    /**
     * @param Project|null $project
     * @param Partners|null $partner
     */
    public function __construct(?Project $project, ?Partners $partner)
    {
        $this->project = $project;
        $this->partner = $partner;
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
     * @return Project|null
     */
    public function getProject(): ?Project
    {
        return $this->project;
    }

    /**
     * @param Project|null $project
     */
    public function setProject(?Project $project): void
    {
        $this->project = $project;
    }

    /**
     * @return Partners|null
     */
    public function getPartner(): ?Partners
    {
        return $this->partner;
    }

    /**
     * @param Partners|null $partner
     */
    public function setPartner(?Partners $partner): void
    {
        $this->partner = $partner;
    }

}