<?php

namespace App\Entity;

use App\Repository\ActionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ActionRepository::class)]
class Action
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column]
    private ?float $iT = null;

    #[ORM\Column]
    private ?float $iKm = null;

    #[ORM\ManyToOne(inversedBy: 'actions')]
    private ?Bien $bien = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getIT(): ?float
    {
        return $this->iT;
    }

    public function setIT(float $iT): static
    {
        $this->iT = $iT;

        return $this;
    }

    public function getIKm(): ?float
    {
        return $this->iKm;
    }

    public function setIKm(float $iKm): static
    {
        $this->iKm = $iKm;

        return $this;
    }

    public function getBien(): ?Bien
    {
        return $this->bien;
    }

    public function setBien(?Bien $bien): static
    {
        $this->bien = $bien;

        return $this;
    }
}
