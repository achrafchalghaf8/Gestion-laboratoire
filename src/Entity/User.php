<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;



#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    public ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    // #[NotBlank(message: "Ajouter le mail de l'utilisateur svp!!!")]
    private ?string $email = null;

    #[ORM\Column]
    // #[NotBlank(message: "Ajouter le role de l'utilisateur svp!!!")]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    // #[NotBlank(message: "Ajouter le mot de passe de l'utilisateur svp!!!")]

    private ?string $password = null;

    #[ORM\Column(length: 255)]
    // #[NotBlank(message: "Ajouter le nom et prénom de l'utilisateur svp!!!")]
    public ?string $nom_prenom = null;

    #[ORM\OneToMany(mappedBy: 'users', targetEntity: Chercheur::class)]
    private Collection $no;

    #[ORM\OneToMany(mappedBy: 'users', targetEntity: Chercheur::class)]
    private Collection $chercheurs;

    #[ORM\OneToMany(mappedBy: 'demandeur', targetEntity: Reservation::class)]
    private Collection $reservations;

    #[ORM\OneToMany(mappedBy: 'auteur', targetEntity: Publication::class)]
    private Collection $publications;

    #[ORM\Column(type: Types::BIGINT)]
    // #[NotBlank(message: "Remplir le numéro téléphone  svp")]
    // #[Assert\Regex(pattern: '/^\d{8}$/')]
    public ?string $telephone = null;

    public function __construct()
    {
        $this->no = new ArrayCollection();
        $this->chercheurs = new ArrayCollection();
        $this->reservations = new ArrayCollection();
        $this->publications = new ArrayCollection();
    }

   

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(?array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }
    public function getSalt(): ?string
    {
        // Retourne un sel à utiliser lors du hachage du mot de passe, ou null si aucun sel n'est utilisé.
        // Dans ce cas, nous n'utilisons pas de sel, nous retournons donc null.
        return null;
    }
    public function setPasswordHash(?string $password): self
    {
        $this->password = $password;

        return $this;
    }
 
    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getNomPrenom(): ?string
    {
        return $this->nom_prenom;
    }

    public function setNomPrenom(?string $nom_prenom): self
    {
        $this->nom_prenom = $nom_prenom;

        return $this;
    }
    public function __toString()
    {
        return $this->nom_prenom;
    }

    // public function getPrenom(): ?string
    // {
    //     return $this->prenom;
    // }

    // public function setPrenom(string $prenom): self
    // {
    //     $this->prenom = $prenom;

    //     return $this;
    // }

    /**
     * @return Collection<int, Chercheur>
     */
    public function getNo(): Collection
    {
        return $this->no;
    }

    // public function addNo(Chercheur $no): self
    // {
    //     if (!$this->no->contains($no)) {
    //         $this->no->add($no);
    //         $no->setUsers($this);
    //     }

    //     return $this;
    // }

    // public function removeNo(Chercheur $no): self
    // {
    //     if ($this->no->removeElement($no)) {
    //         // set the owning side to null (unless already changed)
    //         if ($no->getUsers() === $this) {
    //             $no->setUsers(null);
    //         }
    //     }

    //     return $this;
    // }

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
            $chercheur->setCompte($this);
        }

        return $this;
    }

    public function removeChercheur(Chercheur $chercheur): self
    {
        if ($this->chercheurs->removeElement($chercheur)) {
            // set the owning side to null (unless already changed)
            if ($chercheur->getCompte() === $this) {
                $chercheur->setCompte(null);
            }
        }

        return $this;
    }

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
            $reservation->setDemandeur($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getDemandeur() === $this) {
                $reservation->setDemandeur(null);
            }
        }

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
    //         $publication->setAuteur($this);
    //     }

    //     return $this;
    // }

    // public function removePublication(Publication $publication): self
    // {
    //     if ($this->publications->removeElement($publication)) {
    //         // set the owning side to null (unless already changed)
    //         if ($publication->getAuteur() === $this) {
    //             $publication->setAuteur(null);
    //         }
    //     }

    //     return $this;
    // }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }
    
    

}
