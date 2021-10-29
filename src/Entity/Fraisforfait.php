<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Lignefraisforfait;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Fraisforfait
 *
 * @ORM\Table(name="FraisForfait")
 * @ORM\Entity(repositoryClass="App\Repository\FraisforfaitRepository")
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
     * @var float
     *
     * @ORM\Column(name="montant", type="decimal", precision=5, scale=2, nullable=true, options={"default"=0.0})
     */
    private $montant = 0.0;

    /**
     * @ORM\OneToMany(targetEntity=Lignefraisforfait::class, mappedBy="fraisForfait")
     * @Assert\Valid
     */
    private $lignefraisforfaits;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->lignefraisforfaits = new ArrayCollection();
        $this->lignefraisforfaits->set('ETP', new Lignefraisforfait());
        $this->lignefraisforfaits->set('KM', new Lignefraisforfait());
        $this->lignefraisforfaits->set('NUI', new Lignefraisforfait());
        $this->lignefraisforfaits->set('REP', new Lignefraisforfait());
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
            $lignefraisforfait->setFraisForfait($this);
        }

        return $this;
    }

    public function removeLignefraisforfait(Lignefraisforfait $lignefraisforfait): self
    {
        if ($this->lignefraisforfaits->removeElement($lignefraisforfait)) {
            // set the owning side to null (unless already changed)
            if ($lignefraisforfait->getFraisForfait() === $this) {
                $lignefraisforfait->setFraisForfait(null);
            }
        }

        return $this;
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
}
