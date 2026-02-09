<?php

namespace App\Entity;

use App\Repository\FinancementRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FinancementRepository::class)]
#[ORM\Table(name: 'financement')]
class Financement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 15, scale: 2)]
    private ?string $montant_demande = null;

    #[ORM\Column]
    private ?int $duree_mois = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $objet_financement = null;

    #[ORM\Column(length: 20)]
    private ?string $statut = 'pending'; // pending, approved, rejected

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_demande = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_reponse = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $commentaire_agent = null;

    #[ORM\ManyToOne(targetEntity: Utilisateur::class, inversedBy: 'financements')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateur $client = null;

    #[ORM\ManyToOne(targetEntity: Banque::class, inversedBy: 'financements')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Banque $banque = null;

    public function __construct()
    {
        $this->date_demande = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMontantDemande(): ?string
    {
        return $this->montant_demande;
    }

    public function setMontantDemande(string $montant_demande): static
    {
        $this->montant_demande = $montant_demande;
        return $this;
    }

    public function getDureeMois(): ?int
    {
        return $this->duree_mois;
    }

    public function setDureeMois(int $duree_mois): static
    {
        $this->duree_mois = $duree_mois;
        return $this;
    }

    public function getObjetFinancement(): ?string
    {
        return $this->objet_financement;
    }

    public function setObjetFinancement(string $objet_financement): static
    {
        $this->objet_financement = $objet_financement;
        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): static
    {
        $this->statut = $statut;

        if ($statut !== 'pending' && !$this->date_reponse) {
            $this->date_reponse = new \DateTime();
        }

        return $this;
    }

    public function getDateDemande(): ?\DateTimeInterface
    {
        return $this->date_demande;
    }

    public function setDateDemande(\DateTimeInterface $date_demande): static
    {
        $this->date_demande = $date_demande;
        return $this;
    }

    public function getDateReponse(): ?\DateTimeInterface
    {
        return $this->date_reponse;
    }

    public function setDateReponse(?\DateTimeInterface $date_reponse): static
    {
        $this->date_reponse = $date_reponse;
        return $this;
    }

    public function getCommentaireAgent(): ?string
    {
        return $this->commentaire_agent;
    }

    public function setCommentaireAgent(?string $commentaire_agent): static
    {
        $this->commentaire_agent = $commentaire_agent;
        return $this;
    }

    public function getClient(): ?Utilisateur
    {
        return $this->client;
    }

    public function setClient(?Utilisateur $client): static
    {
        $this->client = $client;
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

    public function getFormattedMontant(): string
    {
        return number_format((float)$this->montant_demande, 2, ',', ' ') . ' TND';
    }

    public function getStatutLabel(): string
    {
        return match($this->statut) {
            'approved' => 'Approuvé',
            'rejected' => 'Rejeté',
            'pending' => 'En attente',
            default => $this->statut,
        };
    }

    public function getStatutBadgeClass(): string
    {
        return match($this->statut) {
            'approved' => 'status-active',
            'rejected' => 'bg-danger',
            'pending' => 'status-pending',
            default => 'bg-secondary',
        };
    }
}
