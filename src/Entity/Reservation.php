<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $NbBillet = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    private ?Session $seance = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getNbBillet(): ?int
    {
        return $this->NbBillet;
    }

    public function setNbBillet(int $NbBillet): static
    {
        $this->NbBillet = $NbBillet;

        return $this;
    }

    public function getSeance(): ?Session
    {
        return $this->seance;
    }

    public function setSeance(?Session $seance): static
    {
        $this->seance = $seance;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
