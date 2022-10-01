<?php

namespace App\Entity;

use App\Repository\VideojocRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
#[ORM\Entity(repositoryClass: VideojocRepository::class)]
#[UniqueEntity('titol')]
class Videojoc
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titol = null;

    #[ORM\Column(nullable: true)]
    private ?int $cantitat = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dataCreacio = null;

    #[ORM\Column(nullable: true)]
    private ?float $preu = null;

    #[ORM\ManyToMany(targetEntity: Genere::class, mappedBy: 'videojocs')]
    private Collection $generes;

    #[ORM\ManyToMany(targetEntity: Plataforma::class, mappedBy: 'videojocs')]
    private Collection $plataformas;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $portada = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $resena = null;

    public function __construct()
    {
        $this->generes = new ArrayCollection();
        $this->plataformas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitol(): ?string
    {
        return $this->titol;
    }

    public function setTitol(string $titol): self
    {
        $this->titol = $titol;

        return $this;
    }

    public function getCantitat(): ?int
    {
        return $this->cantitat;
    }

    public function setCantitat(?int $cantitat): self
    {
        $this->cantitat = $cantitat;

        return $this;
    }

    public function getDataCreacio(): ?\DateTimeInterface
    {
        return $this->dataCreacio;
    }

    public function setDataCreacio(?\DateTimeInterface $dataCreacio): self
    {
        $this->dataCreacio = $dataCreacio;

        return $this;
    }

    public function getPreu(): ?float
    {
        return $this->preu;
    }

    public function setPreu(?float $preu): self
    {
        $this->preu = $preu;

        return $this;
    }

    /**
     * @return Collection<int, Genere>
     */
    public function getGeneres(): Collection
    {
        return $this->generes;
    }

    public function addGenere(Genere $genere): self
    {
        if (!$this->generes->contains($genere)) {
            $this->generes->add($genere);
            $genere->addVideojoc($this);
        }

        return $this;
    }

    public function removeGenere(Genere $genere): self
    {
        if ($this->generes->removeElement($genere)) {
            $genere->removeVideojoc($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Plataforma>
     */
    public function getPlataformas(): Collection
    {
        return $this->plataformas;
    }

    public function addPlataforma(Plataforma $plataforma): self
    {
        if (!$this->plataformas->contains($plataforma)) {
            $this->plataformas->add($plataforma);
            $plataforma->addVideojoc($this);
        }

        return $this;
    }

    public function removePlataforma(Plataforma $plataforma): self
    {
        if ($this->plataformas->removeElement($plataforma)) {
            $plataforma->removeVideojoc($this);
        }

        return $this;
    }

    public function getPortada(): ?string
    {
        return $this->portada;
    }

    public function setPortada(?string $portada): self
    {
        $this->portada = $portada;

        return $this;
    }

    public function getResena(): ?string
    {
        return $this->resena;
    }

    public function setResena(?string $resena): self
    {
        $this->resena = $resena;

        return $this;
    }
}
