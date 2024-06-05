<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ConferenceRepository;
use Symfony\Component\Validator\Constraints\NotBlank;

#[ORM\Entity(repositoryClass: ConferenceRepository::class)]
class Conference
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    // #[NotBlank(message: "Remplir le nom de la conférence svp!!!")]

    public ?string $nom_conference = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
public ?\DateTimeInterface $date_debut = null;

    #[ORM\Column(length: 255)]
    public ?string $dure = null;

    #[ORM\Column(length: 255)]
    public ?string $lieu = null;

    #[ORM\Column(length: 255)]
    public ?string $domaine = null;

    #[ORM\Column(length: 255)]
    public ?string $sponseurs = null;

    #[ORM\Column]
    public ?int $nb_participants = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    #[ORM\Column(length: 5000)]
    private ?string $description = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomConference(): ?string
    {
        return $this->nom_conference;
    }

    public function setNomConference(?string $nom_conference): self
    {
        $this->nom_conference = $nom_conference;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->date_debut;
    }

    public function setDateDebut(\DateTimeInterface $date_debut): self
    {
        $this->date_debut = $date_debut;

        return $this;
    }

    public function getDure(): ?string
    {
        return $this->dure;
    }

    public function setDure(?string $dure): self
    {
        $this->dure = $dure;

        return $this;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(?string $lieu): self
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getDomaine(): ?string
    {
        return $this->domaine;
    }

    public function setDomaine(?string $domaine): self
    {
        $this->domaine = $domaine;

        return $this;
    }

    public function getSponseurs(): ?string
    {
        return $this->sponseurs;
    }

    public function setSponseurs(?string $sponseurs): self
    {
        $this->sponseurs = $sponseurs;

        return $this;
    }

    public function getNbParticipants(): ?int
    {
        return $this->nb_participants;
    }

    public function setNbParticipants(?int $nb_participants): self
    {
        $this->nb_participants = $nb_participants;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
