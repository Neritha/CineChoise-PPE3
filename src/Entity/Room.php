<?php

namespace App\Entity;



use Doctrine\ORM\Mapping as ORM;
use App\Repository\RoomRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use phpDocumentor\Reflection\Types\Nullable;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;

#[ORM\Entity(repositoryClass: RoomRepository::class)]
class Room
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: false)]
    #[NotBlank(message:"Le nom est obligatoire!")]
    #[Length(
        min : 2,
        max : 255,
        minMessage : "Le nom doit comporter au minimum {{ limit }} caractères !",
        maxMessage : "Le nom doit comporter au maximum {{ limit }} caractères !",
    )]
    private ?string $nom = null;

    #[ORM\Column(nullable: false)]
    #[NotBlank(message:"La capacité est obligatoire!")]
    #[GreaterThanOrEqual(value: 1, message: "La valeur doit au moins être égale à 1.")]

    private ?int $capaciter = null;

    #[ORM\OneToMany(mappedBy: 'salle', targetEntity: Session::class)]
    private Collection $sessions;

    public function __construct()
    {
        $this->sessions = new ArrayCollection();
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

    public function getCapaciter(): ?int
    {
        return $this->capaciter;
    }

    public function setCapaciter(int $capaciter): static
    {
        $this->capaciter = $capaciter;

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
            $session->setSalle($this);
        }

        return $this;
    }

    public function removeSession(Session $session): static
    {
        if ($this->sessions->removeElement($session)) {
            // set the owning side to null (unless already changed)
            if ($session->getSalle() === $this) {
                $session->setSalle(null);
            }
        }

        return $this;
    }
}
