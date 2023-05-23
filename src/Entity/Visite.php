<?php

namespace App\Entity;

use App\Repository\VisiteRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VisiteRepository::class)]
class Visite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $nbVisiteurAdulte = null;

    #[ORM\Column(nullable: true)]
    private ?int $nbVisiteurEnfant = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateHeureArrivee = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $DateHeureDepart = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNbVisiteurAdulte(): ?int
    {
        return $this->nbVisiteurAdulte;
    }

    public function setNbVisiteurAdulte(?int $nbVisiteurAdulte): self
    {
        $this->nbVisiteurAdulte = $nbVisiteurAdulte;

        return $this;
    }

    public function getNbVisiteurEnfant(): ?int
    {
        return $this->nbVisiteurEnfant;
    }

    public function setNbVisiteurEnfant(?int $nbVisiteurEnfant): self
    {
        $this->nbVisiteurEnfant = $nbVisiteurEnfant;

        return $this;
    }

    public function getDateHeureArrivee(): ?\DateTimeInterface
    {
        return $this->dateHeureArrivee;
    }

    public function setDateHeureArrivee(\DateTimeInterface $dateHeureArrivee): self
    {
        $this->dateHeureArrivee = $dateHeureArrivee;

        return $this;
    }

    public function getDateHeureDepart(): ?\DateTimeInterface
    {
        return $this->DateHeureDepart;
    }

    public function setDateHeureDepart(?\DateTimeInterface $DateHeureDepart): self
    {
        $this->DateHeureDepart = $DateHeureDepart;

        return $this;
    }
}
