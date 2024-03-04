<?php

namespace App\Entity;

use App\Repository\MovieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MovieRepository::class)]
class Movie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: false)]
    #[Assert\NotBlank(message: "Le titre est obligatoire!")]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: false)]
    #[Assert\NotBlank(message:"La description est obligatoire!")] // la description n'ets pas obligatoire !!
    #[Assert\Length(
        min : 10,
        max : 255,
        minMessage : "La description doit comporter au minimum {{ limit }} caractère !",
        maxMessage : "La description doit comporter au maximum {{ limit }} caractère !",
    )]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: false)] 
    // l'affiche n'est pas obligatoire 
    //#[Assert\NotBlank(message:"L'Affiche est obligatoire!")] // ajouter une affiche n'est pas obligatoire puisque l'affiche par defaut lui sera associé 
    private ?string $affiche = null;

    #[ORM\Column(length: 255, nullable: false)]
    #[Assert\NotBlank(message:"La date est obligatoire!")]
    private ?string $date = null;

    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'movies')]
    #[Assert\NotBlank(message:"Les Catégories sont obligatoire!")]
    private Collection $categories;

    #[ORM\OneToMany(mappedBy: 'film', targetEntity: Session::class)]
    private Collection $sessions;

    #[ORM\Column(type: "datetime_immutable", nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(length: 255, nullable: false)]
    #[Assert\NotBlank(message:"La durée est obligatoire!")]
    private ?string $duree = null;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->sessions = new ArrayCollection();
        $this->setUpdatedAt(new \DateTimeImmutable);
        $this->setAffiche("default.jpg");
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

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getAffiche(): ?string
    {
        return $this->affiche;
    }

    public function setAffiche(string $affiche): static
    {
        $this->affiche = $affiche;

        return $this;
    }

    public function getDate(): ?string
    {
        return $this->date;
    }

    public function setDate(string $date): static
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getcategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): static
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
        }

        return $this;
    }

    public function removeCategory(Category $category): static
    {
        $this->categories->removeElement($category);

        return $this;
    }

    /**
     * @return Collection<int, Session>
     */
    public function getSessions(): Collection
    {
        return $this->sessions;
    }

    public function addSession(Session $session): static
    {
        if (!$this->sessions->contains($session)) {
            $this->sessions->add($session);
            $session->setFilm($this);
        }

        return $this;
    }

    public function removeSession(Session $session): static
    {
        if ($this->sessions->removeElement($session)) {
            // set the owning side to null (unless already changed)
            if ($session->getFilm() === $this) {
                $session->setFilm(null);
            }
        }

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getDuree(): ?string
    {
        return $this->duree;
    }

    public function setDuree(string $duree): static
    {
        $this->duree = $duree;

        return $this;
    }
}
