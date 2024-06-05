<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\PublicationRepository;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


#[ORM\Entity(repositoryClass: PublicationRepository::class)]
#[UniqueEntity('titre')]

class Publication
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    public ?int $id = null;

    #[ORM\Column(length: 255)]
    // #[NotBlank(message: "Remplir le champ titre svp!!!")]
    public ?string $titre = null;

    // #[ORM\Column(length: 255)]
    // public ?string $auteur = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[NotBlank]
    public ?\DateTimeInterface $date_publication = null;

    #[ORM\Column(length: 5000)]
    // #[NotBlank(message: "Remplir le resume de la publication svp!!!")]
    public ?string $resume = null;

    #[ORM\Column(length: 255)]
    // #[NotBlank(message: "Remplir le lien de données svp!!!")]
    public ?string $lien_donnees = null;

    // #[ORM\ManyToOne(inversedBy: 'publications')]
    // private ?Chercheur $chercheurs = null;

    #[ORM\Column(length: 255)]
    // #[NotBlank(message: "Remplir la type de publication svp!!!")]
    private ?string $type = null;

    // #[ORM\ManyToOne(inversedBy: 'publications')]
    // // #[NotBlank(message: "Ajouter auteur de publication svp!!!")]
    // private ?User $auteur = null;

    #[ORM\Column(length: 500)]
    private ?string $details = null;

    #[ORM\Column(length: 255)]
    public ?string $autres_auteurs = null;

    #[ORM\ManyToOne(inversedBy: 'mes_publications')]
    private ?Chercheur $auteur = null;

    // #[ORM\ManyToMany(targetEntity: Chercheur::class, inversedBy: 'publications_participation')]
    // private Collection $auteurs;
    // #[ORM\Column(type: 'string')]
    //   private String $others;


    // public function __construct()
    // {
    //     $this->auteurs = new ArrayCollection();
    // }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(?string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    // public function getAuteur(): ?string
    // {
    //     return $this->auteur;
    // }

    // public function setAuteur(string $auteur): self
    // {
    //     $this->auteur = $auteur;

    //     return $this;
    // }

    public function getDatePublication(): ?\DateTimeInterface
    {
        return $this->date_publication;
    }

    public function setDatePublication(\DateTimeInterface $date_publication): self
    {
        $this->date_publication = $date_publication;

        return $this;
    }

    public function getResume(): ?string
    {
        return $this->resume;
    }

    public function setResume(?string $resume): self
    {
        $this->resume = $resume;

        return $this;
    }

    public function getLienDonnees(): ?string
    {
        return $this->lien_donnees;
    }

    public function setLienDonnees(?string $lien_donnees): self
    {
        $this->lien_donnees = $lien_donnees;

        return $this;
    }

    // public function getChercheurs(): ?Chercheur
    // {
    //     return $this->chercheurs;
    // }

    // public function setChercheurs(?Chercheur $chercheurs): self
    // {
    //     $this->chercheurs = $chercheurs;

    //     return $this;
    // }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    // public function getAuteur(): ?User
    // {
    //     return $this->auteur;
    // }

    // public function setAuteur(?User $auteur): self
    // {
    //     $this->auteur = $auteur;

    //     return $this;
    // }

    public function getDetails(): ?string
    {
        return $this->details;
    }

    public function setDetails(?string $details): self
    {
        $this->details = $details;

        return $this;
    }
    // public function getOthers(): ?String
    // {
    //     return $this->others;
    // }

    // public function setOthers(?String $others): self
    // {
    //     $this->others = $others;

    //     return $this;
    // }

    // /**
    //  * @return Collection<int, Chercheur>
    //  */
    // public function getAuteurs(): Collection
    // {
    //     return $this->auteurs;
    // }

    // public function addAuteur(Chercheur $auteur): self
    // {
    //     if (!$this->auteurs->contains($auteur)) {
    //         $this->auteurs->add($auteur);
    //     }

    //     return $this;
    // }

    // public function removeAuteur(Chercheur $auteur): self
    // {
    //     $this->auteurs->removeElement($auteur);

    //     return $this;
    // }

    public function getAutresAuteurs(): ?string
    {
        return $this->autres_auteurs;
    }

    public function setAutresAuteurs(string $autres_auteurs): self
    {
        $this->autres_auteurs = $autres_auteurs;

        return $this;
    }

    public function getAuteur(): ?Chercheur
    {
        return $this->auteur;
    }

    public function setAuteur(?Chercheur $auteur): self
    {
        $this->auteur = $auteur;

        return $this;
    }
}
