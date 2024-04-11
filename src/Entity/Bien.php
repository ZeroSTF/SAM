<?php

namespace App\Entity;

use App\Repository\BienRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BienRepository::class)]
class Bien
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $etat = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse = null;

    #[ORM\Column]
    private ?float $lat = null;

    #[ORM\Column]
    private ?float $lon = null;

    #[ORM\Column]
    private ?int $x = null;

    #[ORM\Column]
    private ?int $y = null;

    #[ORM\OneToMany(mappedBy: 'bien', targetEntity: Action::class)]
    private Collection $actions;

    #[ORM\OneToMany(mappedBy: 'bien', targetEntity: Maintenance::class)]
    private Collection $maintenances;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'composants')]
    private ?self $bien = null;

    #[ORM\OneToMany(mappedBy: 'bien', targetEntity: self::class)]
    private Collection $composants;

    public function __construct()
    {
        $this->actions = new ArrayCollection();
        $this->maintenances = new ArrayCollection();
        $this->composants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
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

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): static
    {
        $this->etat = $etat;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getLat(): ?float
    {
        return $this->lat;
    }

    public function setLat(float $lat): static
    {
        $this->lat = $lat;

        return $this;
    }

    public function getLon(): ?float
    {
        return $this->lon;
    }

    public function setLon(float $lon): static
    {
        $this->lon = $lon;

        return $this;
    }

    public function getX(): ?int
    {
        return $this->x;
    }

    public function setX(int $x): static
    {
        $this->x = $x;

        return $this;
    }

    public function getY(): ?int
    {
        return $this->y;
    }

    public function setY(int $y): static
    {
        $this->y = $y;

        return $this;
    }

    /**
     * @return Collection<int, Action>
     */
    public function getActions(): Collection
    {
        return $this->actions;
    }

    public function addAction(Action $action): static
    {
        if (!$this->actions->contains($action)) {
            $this->actions->add($action);
            $action->setBien($this);
        }

        return $this;
    }

    public function removeAction(Action $action): static
    {
        if ($this->actions->removeElement($action)) {
            // set the owning side to null (unless already changed)
            if ($action->getBien() === $this) {
                $action->setBien(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Maintenance>
     */
    public function getMaintenances(): Collection
    {
        return $this->maintenances;
    }

    public function addMaintenance(Maintenance $maintenance): static
    {
        if (!$this->maintenances->contains($maintenance)) {
            $this->maintenances->add($maintenance);
            $maintenance->setBien($this);
        }

        return $this;
    }

    public function removeMaintenance(Maintenance $maintenance): static
    {
        if ($this->maintenances->removeElement($maintenance)) {
            // set the owning side to null (unless already changed)
            if ($maintenance->getBien() === $this) {
                $maintenance->setBien(null);
            }
        }

        return $this;
    }

    public function getBien(): ?self
    {
        return $this->bien;
    }

    public function setBien(?self $bien): static
    {
        $this->bien = $bien;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getComposants(): Collection
    {
        return $this->composants;
    }

    public function addComposant(self $composant): static
    {
        if (!$this->composants->contains($composant)) {
            $this->composants->add($composant);
            $composant->setBien($this);
        }

        return $this;
    }

    public function removeComposant(self $composant): static
    {
        if ($this->composants->removeElement($composant)) {
            // set the owning side to null (unless already changed)
            if ($composant->getBien() === $this) {
                $composant->setBien(null);
            }
        }

        return $this;
    }


}
