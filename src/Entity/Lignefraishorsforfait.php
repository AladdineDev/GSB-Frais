<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Lignefraishorsforfait
 *
 * @ORM\Table(name="LigneFraisHorsForfait", indexes={@ORM\Index(name="idFicheFrais", columns={"idFicheFrais"}), @ORM\Index(name="idStatut", columns={"idStatut"})})
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
     * @var string|null
     *
     * @ORM\Column(name="libelle", type="string", length=100, nullable=false, options={"default"="NULL"})
     * @Assert\NotBlank(message = "Le champ libelle doit être renseigné")
     */
    private $libelle;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date", nullable=false)
     * @Assert\NotBlank(message = "Le champ date doit être renseigné")
     * @Assert\Range(
     *      min = "-1 years",
     *      max = "now",
     *      notInRangeMessage = "La date d'engagement doit se situer dans l'année écoulée"
     * )
     */
    private $date;

    /**
     * @var float
     *
     * @ORM\Column(name="montant", type="decimal", precision=10, scale=2, nullable=false, options={"default"=0.0})
     * @Assert\NotBlank(message = "Le champ montant doit être renseigné")
     * @Assert\Positive(message = "Valeur numérique positive attendue")
     */
    private $montant;

    /**
     * @var Statut
     *
     * @ORM\ManyToOne(targetEntity="Statut")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idStatut", referencedColumnName="id", nullable=false)
     * })
     */
    private $idStatut;

    /**
     * @var Fichefrais
     *
     * @ORM\ManyToOne(targetEntity="Fichefrais")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idFicheFrais", referencedColumnName="id", nullable=false)
     * })
     */
    private $idFicheFrais;

    public function __construct()
    {
        $this->date = new \DateTime();
    }

    public function getId(): ?int
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

    public function getIdStatut(): ?Statut
    {
        return $this->idStatut;
    }

    public function setIdStatut(?Statut $idStatut): self
    {
        $this->idStatut = $idStatut;

        return $this;
    }

    public function getIdFicheFrais(): ?Fichefrais
    {
        return $this->idFicheFrais;
    }

    public function setIdFicheFrais(?Fichefrais $idFicheFrais): self
    {
        $this->idFicheFrais = $idFicheFrais;

        return $this;
    }
}
