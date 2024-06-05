<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\MaterielRepository;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Polyfill\Intl\Idn\Resources\unidata\Regex;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;




#[ORM\Entity(repositoryClass: MaterielRepository::class)]
#[UniqueEntity('numero_inventaire')]
#[UniqueEntity('designation')]
 
class Materiel
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    public ?int $id = null;

    #[ORM\Column(length: 255)]
    // #[NotBlank(message: "Entrez la designation du materiel svp!!!")]
    private ?string $designation = null;

    #[ORM\Column(length: 500)]
    // #[NotBlank(message: "Entrez les specifications du materiel svp!!!")]
    private ?string $specifications = null;

    #[ORM\Column]
    private ?int $quantite = null;

    #[ORM\Column(length: 255, nullable: true)]
    // #[NotBlank(message: "Entrez l'image du materiel svp!!!")]
    public ?string $image = null;

    #[ORM\Column(length: 1000)]
    // #[NotBlank(message: "Entrez l'instructions d'utilisation du materiel svp!!!")]
    public ?string $instruc_utilisation = null;

    // #[ORM\Column(length: 255)]
    // // #[Regex(pattern: '/^[a-zA-Z0-9]+$/', message: 'Numero inventaire est composé de chiffres et lettres')]
    // private ?string $numero_inventaire = null;

    #[ORM\OneToMany(mappedBy: 'materiel', targetEntity: Reservation::class)]
    private Collection $reservations;

    #[ORM\Column(length: 255)]
    public ?string $numero_inventaire = null;


    public function __construct()
    {
        $this->reservations = new ArrayCollection();
    }

    // #[ORM\Column(type:"blob", nullable:true)]
    // private $image;
    

    public function getDesignation(): ?string
    {
        return $this->designation;
    }

    public function setDesignation(?string $designation): self
    {
        $this->designation = $designation;

        return $this;
    }

    public function getSpecifications(): ?string
    {
        return $this->specifications;
    }

    public function setSpecifications(?string $specifications): self
    {
        $this->specifications = $specifications;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(?int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }

    // public function setImage($image)
    // {
    //     $this->image = $image;
    // }

    // public function getImage()
    // {
    //     return $this->image;
    // }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getInstrucUtilisation(): ?string
    {
        return $this->instruc_utilisation;
    }

    public function setInstrucUtilisation(?string $instruc_utilisation): self
    {
        $this->instruc_utilisation = $instruc_utilisation;

        return $this;
    }

    // public function getNumInventaire(): ?string
    // {
    //     return $this->numero_inventaire;
    // }

    // public function setNumInventaire(?string $numero_inventaire): self
    // {
    //     $this->numero_inventaire = $numero_inventaire;

    //     return $this;
    // }

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): self
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations->add($reservation);
            $reservation->setMateriel($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getMateriel() === $this) {
                $reservation->setMateriel(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        return $this->designation;
    }

    public function getNumeroInventaire(): ?string
    {
        return $this->numero_inventaire;
    }

    public function setNumeroInventaire(string $numero_inventaire): self
    {
        $this->numero_inventaire = $numero_inventaire;

        return $this;
    }

 
}
