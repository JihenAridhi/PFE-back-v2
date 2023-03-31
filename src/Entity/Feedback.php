<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\FeedbackRepository;
use Doctrine\ORM\Mapping as ORM ;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity(repositoryClass : FeedbackRepository::class)]
//#[ApiResource]
class Feedback extends FeedbackRepository
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id ;

    #[ORM\Column(length: 255)]
    private ?string $name ;

    #[ORM\Column (type: Types::DATE_MUTABLE)]
    private ?\DateTime $date;

    #[ORM\Column(length: 255)]
    private ?string $email ;

    #[ORM\Column(length: 255)]
    private ?string $message ;

    #[ORM\Column]
    private ?int $phone ;

    /**
     * @param string|null $name
     * @param \DateTime|null $date
     * @param string|null $email
     * @param string|null $message
     * @param int|null $phone
     */
    public function __construct(?string $name, ?\DateTime $date, ?string $email, ?string $message, ?int $phone)
    {
        $this->name = $name;
        $this->date = $date;
        $this->email = $email;
        $this->message = $message;
        $this->phone = $phone;
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

    public function getEmail(): ?string
    {
        return $this->email;
    }
    public function setEmail(string $email): self
    {
        $this->email= $email;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getPhone(): ?int
    {
        return $this->phone;
    }

    /**
     * @param int|null $phone
     */
    public function setPhone(?int $phone): void
    {
        $this->phone = $phone;
    }
    public function getMessage(): ?string
    {
        return $this->message;
    }
    public function setMessage(string $message): self
    {
        $this->message = $message;
        return $this;
    }


}