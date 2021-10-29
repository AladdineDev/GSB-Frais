<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\Column(name="mois", type="date", nullable=false, options={"default"="0000-00-00"})
     */
    private $mois;

    /**
     * @var int
     *
     * @ORM\Column(name="nbJustificatifs", type="integer", nullable=true, options={"default"="0"})
     */
    private $nbjustificatifs = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="montantValide", type="decimal", precision=10, scale=2, nullable=true, options={"default"=0.0})
     */
    private $montantvalide = 0.0;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateModif", type="date", nullable=true, options={"default"="0000-00-00"})
     */
    private $datemodif;

    /**
     * @var Etat
     *
     * @ORM\ManyToOne(targetEntity="Etat")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idEtat", referencedColumnName="id")
     * })
     */
    private $idetat;

    /**
     * @var Visiteur
     * 
     * @ORM\ManyToOne(targetEntity="Visiteur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idVisiteur", referencedColumnName="id")
     * })
     */
    private $idvisiteur;

    /**
     * @ORM\OneToMany(targetEntity=Lignefraisforfait::class, mappedBy="ficheFrais")
     */
    private $lignefraisforfaits;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->lignefraisforfaits = new ArrayCollection();
        $this->setMois(new \DateTime());
    }

    /**
     * @return Collection|Lignefraisforfait[]
     */
    public function getLignefraisforfaits(): Collection
    {
        return $this->lignefraisforfaits;
    }

    public function addLignefraisforfait(Lignefraisforfait $lignefraisforfait): self
    {
        if (!$this->lignefraisforfaits->contains($lignefraisforfait)) {
            $this->lignefraisforfaits[] = $lignefraisforfait;
            $lignefraisforfait->setFicheFrais($this);
        }

        return $this;
    }

    public function removeLignefraisforfait(Lignefraisforfait $lignefraisforfait): self
    {
        if ($this->lignefraisforfaits->removeElement($lignefraisforfait)) {
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

    public function getNbjustificatifs(): ?int
    {
        return $this->nbjustificatifs;
    }

    public function setNbjustificatifs(?int $nbjustificatifs): self
    {
        $this->nbjustificatifs = $nbjustificatifs;

        return $this;
    }

    public function getMontantvalide(): ?string
    {
        return $this->montantvalide;
    }

    public function setMontantvalide(?string $montantvalide): self
    {
        $this->montantvalide = $montantvalide;

        return $this;
    }

    public function getDatemodif(): ?\DateTimeInterface
    {
        return $this->datemodif;
    }

    public function setDatemodif(?\DateTimeInterface $datemodif): self
    {
        $this->datemodif = $datemodif;

        return $this;
    }

    public function getIdetat(): ?Etat
    {
        return $this->idetat;
    }

    public function setIdetat(?Etat $idetat): self
    {
        $this->idetat = $idetat;

        return $this;
    }

    public function getIdvisiteur(): ?Visiteur
    {
        return $this->idvisiteur;
    }

    public function setIdvisiteur(?Visiteur $idvisiteur): self
    {
        $this->idvisiteur = $idvisiteur;

        return $this;
    }
}
