<?php

namespace App\Entity;

use App\Repository\GenereRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: GenereRepository::class)]
#[UniqueEntity('genere')]
class Genere
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $genere = null;

    #[ORM\ManyToMany(targetEntity: Videojoc::class, inversedBy: 'generes')]
    private Collection $videojocs;

    public function __construct()
    {
        $this->videojocs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGenere(): ?string
    {
        return $this->genere;
    }

    public function setGenere(string $genere): self
    {
        $this->genere = $genere;

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
