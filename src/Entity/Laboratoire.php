<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\LaboratoireRepository;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: LaboratoireRepository::class)]
#[UniqueEntity('id')]


class Laboratoire
{
    #[ORM\Id]
    #[ORM\Column]
    private ?int $id = 1;

    #[ORM\Column(length: 5000)]
    // #[NotBlank(message: "Entrez la définition de laboratoire svp!!!")]
    private ?string $definition = null;

    #[ORM\Column(length: 255)]
    // #[NotBlank(message: "Entrez l'image' de laboratoire svp!!!")]
    private ?string $image = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDefinition(): ?string
    {
        return $this->definition;
    }

    public function setDefinition(?string $definition): self
    {
        $this->definition = $definition;

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
    
    
}
