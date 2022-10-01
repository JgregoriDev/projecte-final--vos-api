<?php

namespace App\Entity;

use App\Repository\PlataformaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: PlataformaRepository::class)]
#[UniqueEntity('plataforma')]
class Plataforma
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $plataforma = null;

    #[ORM\ManyToMany(targetEntity: Videojoc::class, inversedBy: 'plataformas')]
    private Collection $videojocs;

    public function __construct()
    {
        $this->videojocs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlataforma(): ?string
    {
        return $this->plataforma;
    }

    public function setPlataforma(string $plataforma): self
    {
        $this->plataforma = $plataforma;

        return $this;
    }

    /**
     * @return Collection<int, Videojoc>
     */
    public function getVideojocs(): Collection
    {
        return $this->videojocs;
    }

    public function addVideojoc(Videojoc $videojoc): self
    {
        if (!$this->videojocs->contains($videojoc)) {
            $this->videojocs->add($videojoc);
        }

        return $this;
    }

    public function removeVideojoc(Videojoc $videojoc): self
    {
        $this->videojocs->removeElement($videojoc);

        return $this;
    }
}
