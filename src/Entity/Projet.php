<?php

namespace App\Entity;


use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProjetRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;



#[ORM\Entity(repositoryClass: ProjetRepository::class)]
#[UniqueEntity('titre')]
class Projet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    // #[NotBlank(message: "Entrez le titre du projet svp")]
    public ?string $titre = null;

    #[ORM\Column(length: 5000)]
    // #[NotBlank(message: "Entrez la description du projet svp!!!")]
    public ?string $description = null;


    #[ORM\Column(type: Types::DATE_MUTABLE)]
    // #[NotBlank(message: "Entrez la date debut du projet svp!!!")]
    public ?\DateTimeInterface $date_debut = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    // #[NotBlank(message: "Entrez la date fin du projet svp!!!")]
    #[GreaterThan(propertyPath: "date_debut", message: "Il faut que Date fin doit supérieur a la date début")]
    public ?\DateTimeInterface $date_fin = null;

    // #[ORM\Column(length: 255)]
    // public ?string $membres_equipe = null;

    #[ORM\Column(length: 5000)]
    // #[NotBlank(message: "Entrez la date description svp(meme si le projet n'a pas résultat écrire en cours)")]
    public ?string $description_resultat = null;

    #[ORM\Column(length: 255)]
     private ?string $image = null;

    #[ORM\ManyToMany(targetEntity: Chercheur::class, inversedBy: 'projets')]
    // #[NotBlank(message: "Ajouter les chercheurs du projet svp!!!")]
    private Collection $chercheurs;

    public function __construct()
    {
        $this->chercheurs = new ArrayCollection();
    }

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

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

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->date_fin;
    }

    public function setDateFin(\DateTimeInterface $date_fin): self
    {
        $this->date_fin = $date_fin;

        return $this;
    }

    // public function getMembresEquipe(): ?string
    // {
    //     return $this->membres_equipe;
    // }

    // public function setMembresEquipe(string $membres_equipe): self
    // {
    //     $this->membres_equipe = $membres_equipe;

    //     return $this;
    // }

    public function getDescriptionResultat(): ?string
    {
        return $this->description_resultat;
    }

    public function setDescriptionResultat(?string $description_resultat): self
    {
        $this->description_resultat = $description_resultat;

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

    /**
     * @return Collection<int, Chercheur>
     */
    public function getChercheurs(): Collection
    {
        return $this->chercheurs;
    }

    public function addChercheur(Chercheur $chercheur): self
    {
        if (!$this->chercheurs->contains($chercheur)) {
            $this->chercheurs->add($chercheur);
        }

        return $this;
    }

    public function removeChercheur(Chercheur $chercheur): self
    {
        $this->chercheurs->removeElement($chercheur);

        return $this;
    }
    public function __toString()
    {
        return $this->titre;
    }
    
}