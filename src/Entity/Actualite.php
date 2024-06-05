<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ActualiteRepository;
use Symfony\Component\Validator\Constraints\NotBlank;

#[ORM\Entity(repositoryClass: ActualiteRepository::class)]
class Actualite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    // #[NotBlank(message: "Remplir le titre de l'actualité svp!!!")]

    
    private ?string $titre = null;

    #[ORM\Column(length: 5000)]
    // #[NotBlank(message: "Remplir la description de l'actualité svp!!!")]

    private ?string $description = null;

    #[ORM\Column(length: 255)]
        // #[NotBlank(message: "Ajouter image de l'actualité svp!!!")]
    private ?string $image = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    // #[NotBlank(message: "Ajouter la date de l'actualité svp!!!")]
    private ?\DateTimeInterface $date = null;

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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }
}
