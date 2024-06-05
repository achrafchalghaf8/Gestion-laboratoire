<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ContactRepository;
use Symfony\Component\Validator\Constraints\NotBlank;

#[ORM\Entity(repositoryClass: ContactRepository::class)]
class Contact
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[NotBlank(message: "Entrez votre nom svp!!!")]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[NotBlank(message: "Entrez votre mail svp!!!")]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    #[NotBlank(message: "Entrez votre sujet svp!!!")]
    private ?string $sujet = null;

    #[ORM\Column(length: 255)]
    #[NotBlank(message: "Entrez un message svp!!!")]
    private ?string $message = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    public ?\DateTimeInterface $date_contact = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getSujet(): ?string
    {
        return $this->sujet;
    }

    public function setSujet(string $sujet): self
    {
        $this->sujet = $sujet;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getDateContact(): ?\DateTimeInterface
    {
        return $this->date_contact;
    }

    public function setDateContact(\DateTimeInterface $date_contact): self
    {
        $this->date_contact = $date_contact;

        return $this;
    }
}
