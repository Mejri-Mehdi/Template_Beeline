<?php

namespace App\Entity;

use App\Repository\DocumentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DocumentRepository::class)]
#[ORM\Table(name: 'document')]
class Document
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom_fichier = null;

    #[ORM\Column(length: 50)]
    private ?string $type_document = null;

    #[ORM\Column(length: 255)]
    private ?string $chemin_stockage = null;

    #[ORM\Column(length: 50)]
    private ?string $statut_verification = 'pending';

    #[ORM\ManyToOne(inversedBy: 'documents')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Financement $financement = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomFichier(): ?string
    {
        return $this->nom_fichier;
    }

    public function setNomFichier(string $nom_fichier): static
    {
        $this->nom_fichier = $nom_fichier;
        return $this;
    }

    public function getTypeDocument(): ?string
    {
        return $this->type_document;
    }

    public function setTypeDocument(string $type_document): static
    {
        $this->type_document = $type_document;
        return $this;
    }

    public function getCheminStockage(): ?string
    {
        return $this->chemin_stockage;
    }

    public function setCheminStockage(string $chemin_stockage): static
    {
        $this->chemin_stockage = $chemin_stockage;
        return $this;
    }

    public function getStatutVerification(): ?string
    {
        return $this->statut_verification;
    }

    public function setStatutVerification(string $statut_verification): static
    {
        $this->statut_verification = $statut_verification;
        return $this;
    }

    public function getFinancement(): ?Financement
    {
        return $this->financement;
    }

    public function setFinancement(?Financement $financement): static
    {
        $this->financement = $financement;
        return $this;
    }
}
