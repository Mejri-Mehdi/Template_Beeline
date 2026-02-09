<?php

namespace App\Entity;

use App\Repository\RendezVousRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RendezVousRepository::class)]
#[ORM\Table(name: 'rendez_vous')]
class RendezVous
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_rdv = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $heure_rdv = null;

    #[ORM\Column(length: 20)]
    private ?string $statut = 'pending'; // pending, confirmed, cancelled, completed

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $qr_code = null;

    #[ORM\Column(length: 50, unique: true)]
    private ?string $ticket_reference = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $created_at = null;

    #[ORM\ManyToOne(targetEntity: Utilisateur::class, inversedBy: 'rendezVous')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateur $client = null;

    #[ORM\ManyToOne(targetEntity: Banque::class, inversedBy: 'rendezVous')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Banque $banque = null;

    #[ORM\ManyToOne(targetEntity: Agence::class, inversedBy: 'rendezVous')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Agence $agence = null;

    #[ORM\ManyToOne(targetEntity: Service::class, inversedBy: 'rendezVous')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Service $service = null;

    public function __construct()
    {
        $this->created_at = new \DateTime();
        $this->ticket_reference = $this->generateTicketReference();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateRdv(): ?\DateTimeInterface
    {
        return $this->date_rdv;
    }

    public function setDateRdv(\DateTimeInterface $date_rdv): static
    {
        $this->date_rdv = $date_rdv;
        return $this;
    }

    public function getHeureRdv(): ?\DateTimeInterface
    {
        return $this->heure_rdv;
    }

    public function setHeureRdv(\DateTimeInterface $heure_rdv): static
    {
        $this->heure_rdv = $heure_rdv;
        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): static
    {
        $this->statut = $statut;
        return $this;
    }

    public function getQrCode(): ?string
    {
        return $this->qr_code;
    }

    public function setQrCode(?string $qr_code): static
    {
        $this->qr_code = $qr_code;
        return $this;
    }

    public function getTicketReference(): ?string
    {
        return $this->ticket_reference;
    }

    public function setTicketReference(string $ticket_reference): static
    {
        $this->ticket_reference = $ticket_reference;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): static
    {
        $this->created_at = $created_at;
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

    public function getAgence(): ?Agence
    {
        return $this->agence;
    }

    public function setAgence(?Agence $agence): static
    {
        $this->agence = $agence;
        return $this;
    }

    public function getService(): ?Service
    {
        return $this->service;
    }

    public function setService(?Service $service): static
    {
        $this->service = $service;
        return $this;
    }

    private function generateTicketReference(): string
    {
        return 'RDV-' . strtoupper(uniqid());
    }

    public function getFormattedDate(): string
    {
        return $this->date_rdv ? $this->date_rdv->format('d/m/Y') : '';
    }

    public function getFormattedTime(): string
    {
        return $this->heure_rdv ? $this->heure_rdv->format('H:i') : '';
    }

    public function getStatutBadgeClass(): string
    {
        return match($this->statut) {
            'confirmed' => 'status-active',
            'completed' => 'status-active',
            'pending' => 'status-pending',
            'cancelled' => 'bg-danger',
            default => 'bg-secondary',
        };
    }

    public function getStatutLabel(): string
    {
        return match($this->statut) {
            'confirmed' => 'Confirmé',
            'completed' => 'Terminé',
            'pending' => 'En attente',
            'cancelled' => 'Annulé',
            default => $this->statut,
        };
    }
}
