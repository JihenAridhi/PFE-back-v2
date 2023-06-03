<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM ;
use Symfony\Component\Serializer\Annotation\Ignore;

#[ORM\Entity(repositoryClass : ArticleRepository::class)]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id;

    #[ORM\Column(length: 255)]
    private ?string $title;

    #[ORM\Column(length: 255)]
    private ?string $type;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description;

    /*#[ORM\ManyToMany(targetEntity: Person::class, mappedBy: 'article')]
    private Collection $authors;*/

    #[ORM\Column]
    private ?int $year = null;

    #[ORM\Column(nullable: true)]
    private ?int $month = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $institute = null;

    #[ORM\Column(nullable: true)]
    private ?int $firstPage;

    #[ORM\Column(nullable: true)]
    private ?int $lastPage;

    #[ORM\Column(nullable: true)]
    private ?string $editor;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $volume = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $numero = null;

    #[ORM\Column(length: 255)]
    private ?string $bibtex = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $location = null;

    #[ORM\OneToMany(mappedBy: 'article', targetEntity: ArticlePerson::class)]
    private Collection $articleAuthor;

    #[ORM\Transient]
    private Collection $authors;


    /*#[ORM\Column(length: 255)]
        private ?string $url;*/


    /**
     * @param string|null $title
     * @param string|null $type
     * @param string|null $description
     * @param int|null $year
     */
    public function __construct(?string $title, ?string $type, ?int $year, ?string $description)
    {
        $this->title = $title;
        $this->type = $type;
        $this->description = $description;
        $this->year = $year;
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
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string|null $title
     */
    public function setTitle(?string $title): void
    {
        $this->title = $title;
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
     * @return int|null
     */
    public function getFirstPage(): ?int
    {
        return $this->firstPage;
    }

    /**
     * @param int|null $firstPage
     */
    public function setFirstPage(?int $firstPage): void
    {
        $this->firstPage = $firstPage;
    }

    /**
     * @return int|null
     */
    public function getLastPage(): ?int
    {
        return $this->lastPage;
    }

    /**
     * @param int|null $lastPage
     */
    public function setLastPage(?int $lastPage): void
    {
        $this->lastPage = $lastPage;
    }

    /**
     * @return string|null
     */
    public function getEditor(): ?string
    {
        return $this->editor;
    }

    /**
     * @param string|null $editor
     */
    public function setEditor(?string $editor): void
    {
        $this->editor = $editor;
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

    public function getInstitute(): ?string
    {
        return $this->institute;
    }

    public function setInstitute(?string $institute): self
    {
        $this->institute = $institute;

        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getMonth(): ?int
    {
        return $this->month;
    }

    public function setMonth(?int $month): self
    {
        $this->month = $month;

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

    public function getVolume(): ?string
    {
        return $this->volume;
    }

    public function setVolume(?string $volume): self
    {
        $this->volume = $volume;

        return $this;
    }

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(?string $numero): self
    {
        $this->numero = $numero;

        return $this;
    }

    public function getBibtex(): ?string
    {
        return $this->bibtex;
    }

    public function setBibtex(string $bibtex): self
    {
        $this->bibtex = $bibtex;

        return $this;
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
            $articlePerson->setArticle($this);
        }

        return $this;
    }

    public function removeArticlePerson(ArticlePerson $articlePerson): self
    {
        if ($this->articleAuthor->removeElement($articlePerson)) {
            // set the owning side to null (unless already changed)
            if ($articlePerson->getArticle() === $this) {
                $articlePerson->setArticle(null);
            }
        }

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLocation(): ?string
    {
        return $this->location;
    }

    /**
     * @param string|null $location
     */
    public function setLocation(?string $location): void
    {
        $this->location = $location;
    }

    /**
     * @param Collection $authors
     */
    public function setAuthors(): void
    {
        $this->authors = new ArrayCollection();
        for($i=0; $i<count($this->articleAuthor); $i++)
            $this->authors[$i]=$this->articleAuthor[$i]->getAuthor();
    }

    /**
     * @return Collection
     */
    public function getAuthors(): Collection
    {
        return $this->authors;
    }

}