<?php

namespace App\Entity;

use App\Repository\ConditionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ConditionRepository::class)]
#[ORM\Table(name: 'condition_offre')] // 'condition' is a reserved keyword in SQL often
class Condition
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::FLOAT, nullable: true)]
    private ?float $taux_special = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 15, scale: 2, nullable: true)]
    private ?string $montant_seuil = null;

    #[ORM\Column(nullable: true)]
    private ?int $duree_max = null;

    #[ORM\Column(nullable: true)]
    private ?int $condit_num = null;

    #[ORM\ManyToOne(inversedBy: 'conditions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Offre $offre = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTauxSpecial(): ?float
    {
        return $this->taux_special;
    }

    public function setTauxSpecial(?float $taux_special): static
    {
        $this->taux_special = $taux_special;
        return $this;
    }

    public function getMontantSeuil(): ?string
    {
        return $this->montant_seuil;
    }

    public function setMontantSeuil(?string $montant_seuil): static
    {
        $this->montant_seuil = $montant_seuil;
        return $this;
    }

    public function getDureeMax(): ?int
    {
        return $this->duree_max;
    }

    public function setDureeMax(?int $duree_max): static
    {
        $this->duree_max = $duree_max;
        return $this;
    }

    public function getConditNum(): ?int
    {
        return $this->condit_num;
    }

    public function setConditNum(?int $condit_num): static
    {
        $this->condit_num = $condit_num;
        return $this;
    }

    public function getOffre(): ?Offre
    {
        return $this->offre;
    }

    public function setOffre(?Offre $offre): static
    {
        $this->offre = $offre;
        return $this;
    }
}
