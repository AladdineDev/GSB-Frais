<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Fichefrais
 *
 * @ORM\Table(name="FicheFrais", indexes={@ORM\Index(name="idEtat", columns={"idEtat"}), @ORM\Index(name="idVisiteur", columns={"idVisiteur"})})
 * @ORM\Entity(repositoryClass="App\Repository\FichefraisRepository")
 */
class Fichefrais
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
     * @var \DateTime
     *
     * @ORM\Column(name="mois", type="date", nullable=false)
     */
    private $mois;

    /**
     * @var int
     *
     * @ORM\Column(name="nbJustificatifs", type="integer", nullable=true, options={"default"="0"})
     */
    private $nbJustificatifs = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="montantValide", type="decimal", precision=10, scale=2, nullable=true, options={"default"=0.0})
     */
    private $montantValide = 0.0;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateModif", type="date", nullable=true)
     */
    private $dateModif;

    /**
     * @var Etat
     *
     * @ORM\ManyToOne(targetEntity="Etat")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idEtat", referencedColumnName="id")
     * })
     */
    private $idEtat;

    /**
     * @var Visiteur
     * 
     * @ORM\ManyToOne(targetEntity="Visiteur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idVisiteur", referencedColumnName="id")
     * })
     */
    private $idVisiteur;

    /**
     * @ORM\OneToMany(targetEntity=Lignefraisforfait::class, mappedBy="ficheFrais", cascade={"persist"})
     * @Assert\Valid
     */
    private $ligneFraisForfaits;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->ligneFraisForfaits = new ArrayCollection();
        $this->ligneFraisForfaits->add(new Lignefraisforfait());
        $this->ligneFraisForfaits->add(new Lignefraisforfait());
        $this->ligneFraisForfaits->add(new Lignefraisforfait());
        $this->ligneFraisForfaits->add(new Lignefraisforfait());
        $this->setMois(new \DateTime());
    }

    /**
     * @return Collection|Lignefraisforfait[]
     */
    public function getLignefraisforfaits(): Collection
    {
        return $this->ligneFraisForfaits;
    }

    public function addLignefraisforfait(Lignefraisforfait $lignefraisforfait): self
    {
        if (!$this->ligneFraisForfaits->contains($lignefraisforfait)) {
            $this->ligneFraisForfaits[] = $lignefraisforfait;
            $lignefraisforfait->setFicheFrais($this);
        }

        return $this;
    }

    public function removeLignefraisforfait(Lignefraisforfait $lignefraisforfait): self
    {
        if ($this->ligneFraisForfaits->removeElement($lignefraisforfait)) {
            // set the owning side to null (unless already changed)
            if ($lignefraisforfait->getFicheFrais() === $this) {
                $lignefraisforfait->setFicheFrais(null);
            }
        }

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMois(): ?\DateTimeInterface
    {
        return $this->mois;
    }

    public function setMois(\DateTimeInterface $mois): self
    {
        $this->mois = $mois;

        return $this;
    }

    public function getNbJustificatifs(): ?int
    {
        return $this->nbJustificatifs;
    }

    public function setNbJustificatifs(?int $nbJustificatifs): self
    {
        $this->nbJustificatifs = $nbJustificatifs;

        return $this;
    }

    public function getMontantValide(): ?string
    {
        return $this->montantValide;
    }

    public function setMontantValide(?string $montantValide): self
    {
        $this->montantValide = $montantValide;

        return $this;
    }

    public function getDateModif(): ?\DateTimeInterface
    {
        return $this->dateModif;
    }

    public function setDateModif(?\DateTimeInterface $dateModif): self
    {
        $this->dateModif = $dateModif;

        return $this;
    }

    public function getIdEtat(): ?Etat
    {
        return $this->idEtat;
    }

    public function setIdEtat(?Etat $idEtat): self
    {
        $this->idEtat = $idEtat;

        return $this;
    }

    public function getIdVisiteur(): ?Visiteur
    {
        return $this->idVisiteur;
    }

    public function setIdVisiteur(?Visiteur $idVisiteur): self
    {
        $this->idVisiteur = $idVisiteur;

        return $this;
    }
}
