<?php

namespace App\Entity;

use App\Repository\ExpositionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExpositionRepository::class)]
class Exposition
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nomExpo = null;

    #[ORM\Column(length: 255)]
    private ?string $tarifAdulte = null;

    #[ORM\Column]
    private ?int $tarifEnfant = null;

    #[ORM\Column]
    private ?bool $active = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomExpo(): ?string
    {
        return $this->nomExpo;
    }

    public function setNomExpo(string $nomExpo): self
    {
        $this->nomExpo = $nomExpo;

        return $this;
    }

    public function getTarifAdulte(): ?string
    {
        return $this->tarifAdulte;
    }

    public function setTarifAdulte(string $tarifAdulte): self
    {
        $this->tarifAdulte = $tarifAdulte;

        return $this;
    }

    public function getTarifEnfant(): ?int
    {
        return $this->tarifEnfant;
    }

    public function setTarifEnfant(int $tarifEnfant): self
    {
        $this->tarifEnfant = $tarifEnfant;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }
}
