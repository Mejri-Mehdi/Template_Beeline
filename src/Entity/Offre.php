<?php

namespace App\Entity;

use App\Repository\OffreRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: OffreRepository::class)]
#[ORM\Table(name: 'offre')]
class Offre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $type_offre = null; // credit, compte, carte, epargne, etc.

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_debut = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_fin = null;



    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2, nullable: true)]
    private ?string $taux = null;

    #[ORM\Column]
    private ?bool $active = true;

    #[ORM\ManyToOne(targetEntity: Banque::class, inversedBy: 'offres')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Banque $banque = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 15, scale: 2, nullable: true)]
    private ?string $montant_min = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 15, scale: 2, nullable: true)]
    private ?string $montant_max = null;

    #[ORM\OneToMany(mappedBy: 'offre', targetEntity: Condition::class, orphanRemoval: true)]
    private Collection $conditions;

    public function __construct()
    {
        $this->conditions = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;
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

    public function getTypeOffre(): ?string
    {
        return $this->type_offre;
    }

    public function setTypeOffre(?string $type_offre): static
    {
        $this->type_offre = $type_offre;
        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->date_debut;
    }

    public function setDateDebut(?\DateTimeInterface $date_debut): static
    {
        $this->date_debut = $date_debut;
        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->date_fin;
    }

    public function setDateFin(?\DateTimeInterface $date_fin): static
    {
        $this->date_fin = $date_fin;
        return $this;
    }



    public function getTaux(): ?string
    {
        return $this->taux;
    }

    public function setTaux(?string $taux): static
    {
        $this->taux = $taux;
        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): static
    {
        $this->active = $active;
        return $this;
    }

    public function getBanque(): ?Banque
    {
        return $this->banque;
    }

    public function setBanque(?Banque $banque): static
    {
        $this->banque = $banque;
        return $this;
    }

    public function isCurrentlyActive(): bool
    {
        if (!$this->active) {
            return false;
        }

        $now = new \DateTime();
        
        if ($this->date_debut && $this->date_debut > $now) {
            return false;
        }

        if ($this->date_fin && $this->date_fin < $now) {
            return false;
        }

        return true;
    }

    public function __toString(): string
    {
        return $this->titre ?? '';
    }

    public function getMontantMin(): ?string
    {
        return $this->montant_min;
    }

    public function setMontantMin(?string $montant_min): static
    {
        $this->montant_min = $montant_min;
        return $this;
    }

    public function getMontantMax(): ?string
    {
        return $this->montant_max;
    }

    public function setMontantMax(?string $montant_max): static
    {
        $this->montant_max = $montant_max;
        return $this;
    }

    /**
     * @return Collection<int, Condition>
     */
    public function getConditions(): Collection
    {
        return $this->conditions;
    }

    public function addCondition(Condition $condition): static
    {
        if (!$this->conditions->contains($condition)) {
            $this->conditions->add($condition);
            $condition->setOffre($this);
        }

        return $this;
    }

    public function removeCondition(Condition $condition): static
    {
        if ($this->conditions->removeElement($condition)) {
            // set the owning side to null (unless already changed)
            if ($condition->getOffre() === $this) {
                $condition->setOffre(null);
            }
        }

        return $this;
    }
}
