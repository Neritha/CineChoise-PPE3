<?php

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;
use App\Repository\SessionRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints\NotBlank;

#[ORM\Entity(repositoryClass: SessionRepository::class)]
class Session
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[NotBlank(message:"La date associé est obligatoire!")]
    private ?string $date = null;

    #[ORM\Column(length: 255)]
    #[NotBlank(message:"L' heure associé est obligatoire!")]
    private ?string $heure = null;

    #[ORM\ManyToOne(inversedBy: 'sessions')]

    private ?Movie $film = null;

    #[ORM\ManyToOne(inversedBy: 'sessions')]
    #[NotBlank(message:"Le film associé est obligatoire!")]
    private ?Room $salle = null;

    #[ORM\OneToMany(mappedBy: 'seance', targetEntity: Reservation::class)]
    #[NotBlank(message:"La salle associé est obligatoire!")]
    private Collection $reservations;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
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

    public function getDate(): ?string
    {
        return $this->date;
    }

    public function setDate(string $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getHeure(): ?string
    {
        return $this->heure;
    }

    public function setHeure(string $heure): static
    {
        $this->heure = $heure;

        return $this;
    }

    public function getFilm(): ?Movie
    {
        return $this->film;
    }

    public function setFilm(?Movie $film): static
    {
        $this->film = $film;

        return $this;
    }

    public function getSalle(): ?Room
    {
        return $this->salle;
    }

    public function setSalle(?Room $salle): static
    {
        $this->salle = $salle;

        return $this;
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): static
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations->add($reservation);
            $reservation->setSeance($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): static
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getSeance() === $this) {
                $reservation->setSeance(null);
            }
        }

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }
}
