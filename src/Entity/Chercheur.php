<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ChercheurRepository;
use Doctrine\Common\Collections\Collection;
use phpDocumentor\Reflection\Types\Nullable;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: ChercheurRepository::class)]
class Chercheur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    public ?int $id = null;

    #[ORM\Column(length: 255)]
    // #[NotBlank(message: "Remplir le l'adresse  svp")]
    public ?string $adresse = null;

    // #[ORM\Column(length: 255)]
    // #[NotBlank(message: "Remplir le l'adresse mail svp")]
    // public ?string $email = null;

    // #[ORM\Column(length: 255)]
    // // #[Assert\NotBlank(message: "Remplir le nom et prénom svp")]
    //  public ?string $nom_prenom = null;

    // #[ORM\Column(type: Types::BIGINT)]
    // #[NotBlank(message: "Remplir le numéro téléphone  svp")]
    // #[Assert\Regex(pattern: '/^\d{8}$/')]
    // public ?string $telephone = null;

    #[ORM\Column(length: 255)]
    // #[NotBlank(message: "Remplir la profession  svp")]
    public ?string $profession = null;

    #[ORM\Column(length: 255)]
    // #[NotBlank(message: "Ajouter image du chercheur svp")]
    private ?string $image = null;

    #[ORM\ManyToMany(targetEntity: Projet::class, mappedBy: 'chercheurs')]
    // #[NotBlank(message: "Ajouter les projets du chercheur svp")]
    private Collection $projets;

    #[ORM\Column(name: 'est_supprime', type: 'boolean', options: ['default' => false])]
    private ?bool $est_supprime = false;

    #[ORM\OneToMany(mappedBy: 'chercheurs', targetEntity: Publication::class)]
    private Collection $publications;

    #[ORM\Column(length: 5000)]
    // #[NotBlank(message: "Ajouter la biographie svp")]
    private ?string $biographie = null;

    #[ORM\ManyToOne(inversedBy: 'chercheurs')]
    // #[NotBlank(message: "Ajouter le compte du chercheur' svp")]
    private ?User $compte = null;

    #[ORM\OneToMany(mappedBy: 'auteur', targetEntity: Publication::class)]
    private Collection $mes_publications;

    // #[ORM\ManyToMany(targetEntity: Publication::class, mappedBy: 'auteurs')]
    // private Collection $publications_participation;
    

    public function __construct()
    {
        $this->projets = new ArrayCollection();
        $this->publications = new ArrayCollection();
        // $this->publications_participation = new ArrayCollection();
        $this->mes_publications = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    // public function getEmail(): ?string
    // {
    //     return $this->email;
    // }

    // public function setEmail(string $email): self
    // {
    //     $this->email = $email;

    //     return $this;
    // }

    // public function getNomPrenom(): ?string
    // {
    //     return $this->nom_prenom;
    // }

    // public function setNomPrenom(?string $nom_prenom): self
    // {
    //     $this->nom_prenom = $nom_prenom;

    //     return $this;
    // }

    // public function getTelephone(): ?string
    // {
    //     return $this->telephone;
    // }

    // public function setTelephone(string $telephone): self
    // {
    //     $this->telephone = $telephone;

    //     return $this;
    // }

    public function getProfession(): ?string
    {
        return $this->profession;
    }

    public function setProfession(?string $profession): self
    {
        $this->profession = $profession;

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
     * @return Collection<int, Projet>
     */
    public function getProjets(): Collection
    {
        return $this->projets;
    }

    public function addProjet(Projet $projet): self
    {
        if (!$this->projets->contains($projet)) {
            $this->projets->add($projet);
            $projet->addChercheur($this);
        }

        return $this;
    }

    public function removeProjet(Projet $projet): self
    {
        if ($this->projets->removeElement($projet)) {
            $projet->removeChercheur($this);
        }

        return $this;
    }
    public function __toString()
    {
        return $this->compte;
    }

    public function isEstSupprime(): ?bool
    {
        return $this->est_supprime;
    }

    public function setEstSupprime(bool $est_supprime): self
    {
        $this->est_supprime = $est_supprime;

        return $this;
    }

    /**
     * @return Collection<int, Publication>
     */
    public function getPublications(): Collection
    {
        return $this->publications;
    }

    // public function addPublication(Publication $publication): self
    // {
    //     if (!$this->publications->contains($publication)) {
    //         $this->publications->add($publication);
    //         $publication->setChercheurs($this);
    //     }

    //     return $this;
    // }

    // public function removePublication(Publication $publication): self
    // {
    //     if ($this->publications->removeElement($publication)) {
    //         // set the owning side to null (unless already changed)
    //         if ($publication->getChercheurs() === $this) {
    //             $publication->setChercheurs(null);
    //         }
    //     }

    //     return $this;
    // }

    public function getBiographie(): ?string
    {
        return $this->biographie;
    }

    public function setBiographie(?string $biographie): self
    {
        $this->biographie = $biographie;

        return $this;
    }
    // public function __toString()
    // {
    //     return $this->nom_prenom;
    // }

    public function getCompte(): ?User
    {
        return $this->compte;
    }

    public function setCompte(?User $compte): self
    {
        $this->compte = $compte;

        return $this;
    }

    // /**
    //  * @return Collection<int, Publication>
    //  */
    // public function getPublicationsParticipation(): Collection
    // {
    //     return $this->publications_participation;
    // }

    // public function addPublicationsParticipation(Publication $publicationsParticipation): self
    // {
    //     if (!$this->publications_participation->contains($publicationsParticipation)) {
    //         $this->publications_participation->add($publicationsParticipation);
    //         $publicationsParticipation->addAuteur($this);
    //     }

    //     return $this;
    // }

    // public function removePublicationsParticipation(Publication $publicationsParticipation): self
    // {
    //     if ($this->publications_participation->removeElement($publicationsParticipation)) {
    //         $publicationsParticipation->removeAuteur($this);
    //     }

    //     return $this;
    // }

    /**
     * @return Collection<int, Publication>
     */
    public function getMesPublications(): Collection
    {
        return $this->mes_publications;
    }

    public function addMesPublication(Publication $mesPublication): self
    {
        if (!$this->mes_publications->contains($mesPublication)) {
            $this->mes_publications->add($mesPublication);
            $mesPublication->setAuteur($this);
        }

        return $this;
    }

    public function removeMesPublication(Publication $mesPublication): self
    {
        if ($this->mes_publications->removeElement($mesPublication)) {
            // set the owning side to null (unless already changed)
            if ($mesPublication->getAuteur() === $this) {
                $mesPublication->setAuteur(null);
            }
        }

        return $this;
    }
}



















// ///////////////////////
// <?php

// namespace App\Entity;

// use Doctrine\DBAL\Types\Types;
// use Doctrine\ORM\Mapping as ORM;
// use App\Repository\ChercheurRepository;
// use Doctrine\Common\Collections\Collection;
// use Doctrine\Common\Collections\ArrayCollection;
// use Symfony\Component\Validator\Constraints\NotBlank;
// use Symfony\Component\Validator\Constraints as Assert;


// #[ORM\Entity(repositoryClass: ChercheurRepository::class)]
// class Chercheur
// {
//     #[ORM\Id]
//     #[ORM\GeneratedValue]
//     #[ORM\Column]
//     public ?int $id = null;

//     #[ORM\Column(length: 255)]
//     // #[NotBlank(message: "Remplir le l'adresse  svp")]
//     public ?string $adresse = null;

//     // #[ORM\Column(length: 255)]
//     // #[NotBlank(message: "Remplir le l'adresse mail svp")]
//     // public ?string $email = null;

//     #[ORM\Column(length: 255)]
//     // #[NotBlank(message: "Remplir le nom et prénom  svp")]
//     public ?string $nom_prenom = null;

//     // #[ORM\Column(type: Types::BIGINT)]
//     // #[NotBlank(message: "Remplir le numéro téléphone  svp")]
//     // #[Assert\Regex(pattern: '/^\d{8}$/')]
//     // public ?string $telephone = null;

//     #[ORM\Column(length: 255)]
//     // #[NotBlank(message: "Remplir la profession  svp")]
//     public ?string $profession = null;

//     #[ORM\Column(length: 255)]
//     // #[NotBlank(message: "Ajouter image du chercheur svp")]
//     private ?string $image = null;

//     #[ORM\ManyToMany(targetEntity: Projet::class, mappedBy: 'chercheurs')]
//     // #[NotBlank(message: "Ajouter les projets du chercheur svp")]
//     private Collection $projets;

//     #[ORM\Column(name: 'est_supprime', type: 'boolean', options: ['default' => false])]
//     private ?bool $est_supprime = false;

//     #[ORM\OneToMany(mappedBy: 'chercheurs', targetEntity: Publication::class)]
//     private Collection $publications;

//     #[ORM\Column(length: 5000)]
//     // #[NotBlank(message: "Ajouter la biographie svp")]
//     private ?string $biographie = null;

//     #[ORM\ManyToOne(inversedBy: 'chercheurs')]
//     // #[NotBlank(message: "Ajouter le compte du chercheur' svp")]
//     private ?User $compte = null;

//     #[ORM\ManyToMany(targetEntity: Publication::class, mappedBy: 'auteurs')]
//     private Collection $publications_participation;
    

//     public function __construct()
//     {
//         $this->projets = new ArrayCollection();
//         $this->publications = new ArrayCollection();
//         $this->publications_participation = new ArrayCollection();
//     }

//     public function getId(): ?int
//     {
//         return $this->id;
//     }

//     public function getAdresse(): ?string
//     {
//         return $this->adresse;
//     }

//     public function setAdresse(string $adresse): self
//     {
//         $this->adresse = $adresse;

//         return $this;
//     }

//     // public function getEmail(): ?string
//     // {
//     //     return $this->email;
//     // }

//     // public function setEmail(string $email): self
//     // {
//     //     $this->email = $email;

//     //     return $this;
//     // }

//     public function getNomPrenom(): ?string
//     {
//         return $this->nom_prenom;
//     }

//     public function setNomPrenom(string $nom_prenom): self
//     {
//         $this->nom_prenom = $nom_prenom;

//         return $this;
//     }

//     // public function getTelephone(): ?string
//     // {
//     //     return $this->telephone;
//     // }

//     // public function setTelephone(string $telephone): self
//     // {
//     //     $this->telephone = $telephone;

//     //     return $this;
//     // }

//     public function getProfession(): ?string
//     {
//         return $this->profession;
//     }

//     public function setProfession(string $profession): self
//     {
//         $this->profession = $profession;

//         return $this;
//     }

//     public function getImage(): ?string
//     {
//         return $this->image;
//     }

//     public function setImage(string $image): self
//     {
//         $this->image = $image;

//         return $this;
//     }

//     /**
//      * @return Collection<int, Projet>
//      */
//     public function getProjets(): Collection
//     {
//         return $this->projets;
//     }

//     public function addProjet(Projet $projet): self
//     {
//         if (!$this->projets->contains($projet)) {
//             $this->projets->add($projet);
//             $projet->addChercheur($this);
//         }

//         return $this;
//     }

//     public function removeProjet(Projet $projet): self
//     {
//         if ($this->projets->removeElement($projet)) {
//             $projet->removeChercheur($this);
//         }

//         return $this;
//     }
//     public function __toString()
//     {
//         return $this->nom_prenom;
//     }

//     public function isEstSupprime(): ?bool
//     {
//         return $this->est_supprime;
//     }

//     public function setEstSupprime(bool $est_supprime): self
//     {
//         $this->est_supprime = $est_supprime;

//         return $this;
//     }

//     /**
//      * @return Collection<int, Publication>
//      */
//     public function getPublications(): Collection
//     {
//         return $this->publications;
//     }

//     // public function addPublication(Publication $publication): self
//     // {
//     //     if (!$this->publications->contains($publication)) {
//     //         $this->publications->add($publication);
//     //         $publication->setChercheurs($this);
//     //     }

//     //     return $this;
//     // }

//     // public function removePublication(Publication $publication): self
//     // {
//     //     if ($this->publications->removeElement($publication)) {
//     //         // set the owning side to null (unless already changed)
//     //         if ($publication->getChercheurs() === $this) {
//     //             $publication->setChercheurs(null);
//     //         }
//     //     }

//     //     return $this;
//     // }

//     public function getBiographie(): ?string
//     {
//         return $this->biographie;
//     }

//     public function setBiographie(string $biographie): self
//     {
//         $this->biographie = $biographie;

//         return $this;
//     }
//     // public function __toString()
//     // {
//     //     return $this->nom_prenom;
//     // }

//     public function getCompte(): ?User
//     {
//         return $this->compte;
//     }

//     public function setCompte(?User $compte): self
//     {
//         $this->compte = $compte;

//         return $this;
//     }

//     /**
//      * @return Collection<int, Publication>
//      */
//     public function getPublicationsParticipation(): Collection
//     {
//         return $this->publications_participation;
//     }

//     // public function addPublicationsParticipation(Publication $publicationsParticipation): self
//     // {
//     //     if (!$this->publications_participation->contains($publicationsParticipation)) {
//     //         $this->publications_participation->add($publicationsParticipation);
//     //         $publicationsParticipation->addAuteur($this);
//     //     }

//     //     return $this;
//     // }

//     // public function removePublicationsParticipation(Publication $publicationsParticipation): self
//     // {
//     //     if ($this->publications_participation->removeElement($publicationsParticipation)) {
//     //         $publicationsParticipation->removeAuteur($this);
//     //     }

//     //     return $this;
//     // }
// }