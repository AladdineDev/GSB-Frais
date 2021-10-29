<?php

namespace App\Entity;

use App\Repository\LignefraisforfaitRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="LigneFraisForfait")
 * @ORM\Entity(repositoryClass="App\Repository\LignefraisforfaitRepository")
 */
class Lignefraisforfait
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $quantite;

    /**
     * @ORM\ManyToOne(targetEntity=Fichefrais::class, inversedBy="lignefraisforfaits")
     */
    private $ficheFrais;

    /**
     * @ORM\ManyToOne(targetEntity=Fraisforfait::class, inversedBy="lignefraisforfaits")
     */
    private $fraisForfait;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(?int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getFicheFrais(): ?Fichefrais
    {
        return $this->ficheFrais;
    }

    public function setFicheFrais(?Fichefrais $ficheFrais): self
    {
        $this->ficheFrais = $ficheFrais;

        return $this;
    }

    public function getFraisForfait(): ?Fraisforfait
    {
        return $this->fraisForfait;
    }

    public function setFraisForfait(?Fraisforfait $fraisForfait): self
    {
        $this->fraisForfait = $fraisForfait;

        return $this;
    }
}
