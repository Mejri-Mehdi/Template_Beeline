<?php

namespace App\Entity;

use App\Repository\BanqueRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BanqueRepository::class)]
#[ORM\Table(name: 'banque')]
class Banque
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom_bq = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $site_web = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $telephone_bq = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $email_bq = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $logo = null;

    #[ORM\Column(length: 20)]
    private ?string $statut = 'pending'; // pending, active, rejected

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\OneToMany(mappedBy: 'banque', targetEntity: Agence::class, orphanRemoval: true, cascade: ['persist', 'remove'])]
    private Collection $agences;

    #[ORM\OneToMany(mappedBy: 'banque', targetEntity: Utilisateur::class)]
    private Collection $utilisateurs;

    #[ORM\OneToMany(mappedBy: 'banque', targetEntity: Service::class, orphanRemoval: true, cascade: ['persist', 'remove'])]
    private Collection $services;

    #[ORM\OneToMany(mappedBy: 'banque', targetEntity: Offre::class, orphanRemoval: true, cascade: ['persist', 'remove'])]
    private Collection $offres;

    #[ORM\OneToMany(mappedBy: 'banque', targetEntity: RendezVous::class, orphanRemoval: true)]
    private Collection $rendezVous;

    #[ORM\OneToMany(mappedBy: 'banque', targetEntity: Financement::class, orphanRemoval: true)]
    private Collection $financements;

    public function __construct()
    {
        $this->agences = new ArrayCollection();
        $this->utilisateurs = new ArrayCollection();
        $this->services = new ArrayCollection();
        $this->offres = new ArrayCollection();
        $this->rendezVous = new ArrayCollection();
        $this->financements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomBq(): ?string
    {
        return $this->nom_bq;
    }

    public function setNomBq(string $nom_bq): static
    {
        $this->nom_bq = $nom_bq;
        return $this;
    }

    public function getSiteWeb(): ?string
    {
        return $this->site_web;
    }

    public function setSiteWeb(?string $site_web): static
    {
        $this->site_web = $site_web;
        return $this;
    }

    public function getTelephoneBq(): ?string
    {
        return $this->telephone_bq;
    }

    public function setTelephoneBq(?string $telephone_bq): static
    {
        $this->telephone_bq = $telephone_bq;
        return $this;
    }

    public function getEmailBq(): ?string
    {
        return $this->email_bq;
    }

    public function setEmailBq(?string $email_bq): static
    {
        $this->email_bq = $email_bq;
        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): static
    {
        $this->logo = $logo;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return Collection<int, Agence>
     */
    public function getAgences(): Collection
    {
        return $this->agences;
    }

    public function addAgence(Agence $agence): static
    {
        if (!$this->agences->contains($agence)) {
            $this->agences->add($agence);
            $agence->setBanque($this);
        }

        return $this;
    }

    public function removeAgence(Agence $agence): static
    {
        if ($this->agences->removeElement($agence)) {
            if ($agence->getBanque() === $this) {
                $agence->setBanque(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Utilisateur>
     */
    public function getUtilisateurs(): Collection
    {
        return $this->utilisateurs;
    }

    public function addUtilisateur(Utilisateur $utilisateur): static
    {
        if (!$this->utilisateurs->contains($utilisateur)) {
            $this->utilisateurs->add($utilisateur);
            $utilisateur->setBanque($this);
        }

        return $this;
    }

    public function removeUtilisateur(Utilisateur $utilisateur): static
    {
        if ($this->utilisateurs->removeElement($utilisateur)) {
            if ($utilisateur->getBanque() === $this) {
                $utilisateur->setBanque(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Service>
     */
    public function getServices(): Collection
    {
        return $this->services;
    }

    public function addService(Service $service): static
    {
        if (!$this->services->contains($service)) {
            $this->services->add($service);
            $service->setBanque($this);
        }

        return $this;
    }

    public function removeService(Service $service): static
    {
        if ($this->services->removeElement($service)) {
            if ($service->getBanque() === $this) {
                $service->setBanque(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Offre>
     */
    public function getOffres(): Collection
    {
        return $this->offres;
    }

    public function addOffre(Offre $offre): static
    {
        if (!$this->offres->contains($offre)) {
            $this->offres->add($offre);
            $offre->setBanque($this);
        }

        return $this;
    }

    public function removeOffre(Offre $offre): static
    {
        if ($this->offres->removeElement($offre)) {
            if ($offre->getBanque() === $this) {
                $offre->setBanque(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, RendezVous>
     */
    public function getRendezVous(): Collection
    {
        return $this->rendezVous;
    }

    public function addRendezVous(RendezVous $rendezVous): static
    {
        if (!$this->rendezVous->contains($rendezVous)) {
            $this->rendezVous->add($rendezVous);
            $rendezVous->setBanque($this);
        }

        return $this;
    }

    public function removeRendezVous(RendezVous $rendezVous): static
    {
        if ($this->rendezVous->removeElement($rendezVous)) {
            if ($rendezVous->getBanque() === $this) {
                $rendezVous->setBanque(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Financement>
     */
    public function getFinancements(): Collection
    {
        return $this->financements;
    }

    public function addFinancement(Financement $financement): static
    {
        if (!$this->financements->contains($financement)) {
            $this->financements->add($financement);
            $financement->setBanque($this);
        }

        return $this;
    }

    public function removeFinancement(Financement $financement): static
    {
        if ($this->financements->removeElement($financement)) {
            if ($financement->getBanque() === $this) {
                $financement->setBanque(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->nom_bq ?? '';
    }
}
