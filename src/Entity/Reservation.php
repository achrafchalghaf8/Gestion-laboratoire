<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ReservationRepository;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\GreaterThan;



#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[NotBlank(message: "Remplir la date debut de la reservation svp!!!")]

    public ?\DateTimeInterface $date_debut = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[NotBlank(message: "Remplir la date fin de la reservation svp!!!")]
    #[GreaterThan(propertyPath: "date_debut", message: "Il faut que Date fin doit supérieur a la date début")]


    public ?\DateTimeInterface $date_fin = null;

    // #[ORM\Column(type: Types::BIGINT)]
    // #[NotBlank(message: "Ajoutez votre  telephone svp!!!")]
    // #[Assert\Regex(pattern: '/^\d{8}$/')]
    // public ?string $num_tel = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    #[NotBlank(message: "Ajoutez un demandeur de la reservation svp!!!")]
    private ?User $demandeur = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    #[NotBlank(message: "Ajoutez le materiel a reserver svp!!!")]
    private ?Materiel $materiel = null;

    #[ORM\Column( nullable: true)]
    private ?bool $valide = null;

    #[ORM\Column]
    private ?bool $retourne = false;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $numero_serie = null;

    

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->date_fin;
    }

    public function setDateFin(\DateTimeInterface $date_fin): self
    {
        $this->date_fin = $date_fin;

        return $this;
    }

    // public function getNumTel(): ?string
    // {
    //     return $this->num_tel;
    // }

    // public function setNumTel(string $num_tel): self
    // {
    //     $this->num_tel = $num_tel;

    //     return $this;
    // }

    public function getDemandeur(): ?User
    {
        return $this->demandeur;
    }

    public function setDemandeur(?User $demandeur): self
    {
        $this->demandeur = $demandeur;

        return $this;
    }

    public function getMateriel(): ?Materiel
    {
        return $this->materiel;
    }

    public function setMateriel(?Materiel $materiel): self
    {
        $this->materiel = $materiel;

        return $this;
    }

    public function isValide(): ?bool
    {
        return $this->valide;
    }

    public function setValide(bool $valide): self
    {
        $this->valide = $valide;

        return $this;
    }

    public function isRetourne(): ?bool
    {
        return $this->retourne;
    }
    
    public function setRetourne(bool $retourne): self
    {
        $this->retourne = $retourne;
    
        return $this;
    }

    public function getNumeroSerie(): ?string
    {
        return $this->numero_serie;
    }

    public function setNumeroSerie(?string $numero_serie): self
    {
        $this->numero_serie = $numero_serie;

        return $this;
    }
    

   
}
