<?php

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;
use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Expr\Value;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: false)]
    #[NotBlank(message:"Le nom est obligatoire est obligatoire!")]
    #[Length(
        min : 2,
        max : 255,
        minMessage : "Le nom doit comporter au minimum {{ limit }} caractères !",
        maxMessage : "Le nom doit comporter au maximum {{ limit }} caractères !",
    )]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    //#[NotBlank(message:"La description est obligatoire!")] la description n'est pas obligatoire !
    #[Length(
        min : 2,
        max : 255,
        minMessage : "La description doit comporter au minimum {{ limit }} caractères !",
        maxMessage : "La description doit comporter au maximum {{ limit }} caractères !",
    )]
    private ?string $description = null;

    #[ORM\ManyToMany(targetEntity: Movie::class, mappedBy: 'categories')] 
    private Collection $movies;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $couleur = null;

    public function __construct()
    {
        $this->movies = new ArrayCollection();
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

    /**
     * @return Collection<int, Movie>
     */
    public function getMovies(): Collection
    {
        return $this->movies;
    }

    public function addMovie(Movie $movie): static
    {
        if (!$this->movies->contains($movie)) {
            $this->movies->add($movie);
            $movie->addCategory($this);
        }

        return $this;
    }

    public function removeMovie(Movie $movie): static
    {
        if ($this->movies->removeElement($movie)) {
            $movie->removeCategory($this);
        }

        return $this;
    }

    public function getCouleur(): ?string
    {
        return $this->couleur;
    }

    public function setCouleur(?string $couleur): static
    {
        $this->couleur = $couleur;

        return $this;
    }
}
