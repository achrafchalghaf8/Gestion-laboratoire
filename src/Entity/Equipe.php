<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\EquipeRepository;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: EquipeRepository::class)]
#[UniqueEntity('id, message="Cet ID est déjà utilisé.')]
class Equipe
{
    #[ORM\Id]
    #[ORM\Column]
    private ?int $id = 1;

    #[ORM\Column(length: 5000)]
    // #[NotBlank(message: "Entrez l'historique de l'équipe svp!!!")]
    private ?string $historique = null;

    #[ORM\Column(length: 5000)]
    // #[NotBlank(message: "Entrez l'objectifs de l'équipe svp!!!")]
    private ?string $objectifs = null;

    #[ORM\Column(length: 255)]
    // #[NotBlank(message: "Entrez l'image de l'équipe svp!!!")]
    private ?string $image = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHistorique(): ?string
    {
        return $this->historique;
    }

    public function setHistorique(?string $historique): self
    {
        $this->historique = $historique;

        return $this;
    }

    public function getObjectifs(): ?string
    {
        return $this->objectifs;
    }

    public function setObjectifs(?string $objectifs): self
    {
        $this->objectifs = $objectifs;

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
