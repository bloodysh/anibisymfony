<?php

namespace App\Entity;

use App\Repository\VisiteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    #[ORM\ManyToMany(targetEntity: Exposition::class, inversedBy: 'visites')]
    private Collection $expositions;

    public function __construct()
    {
        $this->expositions = new ArrayCollection();
    }


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

    /**
     * @return Collection<int, Exposition>
     */
    public function getExpositions(): Collection
    {
        return $this->expositions;
    }

    public function addExposition(Exposition $exposition): self
    {
        if (!$this->expositions->contains($exposition)) {
            $this->expositions->add($exposition);
        }
        return $this;
    }

    public function removeExposition(Exposition $exposition): self
    {
        $this->expositions->removeElement($exposition);

        return $this;
    }

    public function calculerTarif(): float
    {
        $totalTarif= 0;
        foreach ($this -> getExpositions() as $exposition)
        {
            $totalTarifAdultes = $this->getNbVisiteurAdulte() * $exposition->getTarifAdulte() ;
            $totalTarifEnfants = $this->getNbVisiteurEnfant() * $exposition ->getTarifEnfant();
            $totalTarif+= $totalTarifAdultes + $totalTarifEnfants;
        }
        return $totalTarif;

    }




}
