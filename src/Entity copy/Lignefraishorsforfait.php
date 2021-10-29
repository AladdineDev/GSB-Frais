<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Lignefraishorsforfait
 *
 * @ORM\Table(name="LigneFraisHorsForfait", indexes={@ORM\Index(name="idFicheFrais", columns={"idFicheFrais"})})
 * @ORM\Entity(repositoryClass="App\Repository\LignefraishorsforfaitRepository")
 */
class Lignefraishorsforfait
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="idVisiteur", type="string", length=4, nullable=false, options={"fixed"=true})
     */
    private $idvisiteur;

    /**
     * @var string
     *
     * @ORM\Column(name="mois", type="string", length=6, nullable=false, options={"fixed"=true})
     */
    private $mois;

    /**
     * @var string|null
     *
     * @ORM\Column(name="libelle", type="string", length=100, nullable=true, options={"default"="NULL"})
     */
    private $libelle = 'NULL';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date", nullable=true, options={"default"="0000-00-00"})
     */
    private $date = '0000-00-00';

    /**
     * @var float
     *
     * @ORM\Column(name="montant", type="decimal", precision=10, scale=2, nullable=true, options={"default"=0.0})
     */
    private $montant = 0.0;

    /**
     * @var Fichefrais
     *
     * @ORM\ManyToOne(targetEntity="Fichefrais")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idFicheFrais", referencedColumnName="id")
     * })
     */
    private $idfichefrais;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdvisiteur(): ?string
    {
        return $this->idvisiteur;
    }

    public function setIdvisiteur(string $idvisiteur): self
    {
        $this->idvisiteur = $idvisiteur;

        return $this;
    }

    public function getMois(): ?string
    {
        return $this->mois;
    }

    public function setMois(string $mois): self
    {
        $this->mois = $mois;

        return $this;
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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

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

    public function getIdfichefrais(): ?Fichefrais
    {
        return $this->idfichefrais;
    }

    public function setIdfichefrais(?Fichefrais $idfichefrais): self
    {
        $this->idfichefrais = $idfichefrais;

        return $this;
    }
}
