<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Fraisforfait
 *
 * @ORM\Table(name="FraisForfait")
 * @ORM\Entity(repositoryClass="App\Repository\FraisForfaitRepository")
 */
class Fraisforfait
{
    /**
     * @var string
     *
     * @ORM\Column(name="id", type="string", length=3, nullable=false, options={"fixed"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="libelle", type="string", length=20, nullable=true, options={"default"="NULL","fixed"=true})
     */
    private $libelle = 'NULL';

    /**
     * @var string|null
     *
     * @ORM\Column(name="montant", type="decimal", precision=5, scale=2, nullable=true, options={"default"="NULL"})
     */
    private $montant = 'NULL';

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Fichefrais", mappedBy="idfraisforfait")
     */
    private $idvisiteur;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->idvisiteur = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(?string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getMontant(): ?string
    {
        return $this->montant;
    }

    public function setMontant(?string $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    /**
     * @return Collection|Fichefrais[]
     */
    public function getIdvisiteur(): Collection
    {
        return $this->idvisiteur;
    }

    public function addIdvisiteur(Fichefrais $idvisiteur): self
    {
        if (!$this->idvisiteur->contains($idvisiteur)) {
            $this->idvisiteur[] = $idvisiteur;
            $idvisiteur->addIdfraisforfait($this);
        }

        return $this;
    }

    public function removeIdvisiteur(Fichefrais $idvisiteur): self
    {
        if ($this->idvisiteur->removeElement($idvisiteur)) {
            $idvisiteur->removeIdfraisforfait($this);
        }

        return $this;
    }
}
