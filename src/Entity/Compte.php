<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\CompteRepository")
 */
class Compte
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $solde;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $numbcompte;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\partenaire", inversedBy="comptes")
     */
    private $idpartenaire;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Depot", mappedBy="idcompte")
     */
    private $depots;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="idcompte")
     */
    private $users;

    public function __construct()
    {
        $this->depots = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSolde(): ?int
    {
        return $this->solde;
    }

    public function setSolde(?int $solde): self
    {
        $this->solde = $solde;

        return $this;
    }

    public function getNumbcompte(): ?int
    {
        return $this->numbcompte;
    }

    public function setNumbcompte(?int $numbcompte): self
    {
        $this->numbcompte = $numbcompte;

        return $this;
    }

    public function getIdpartenaire(): ?partenaire
    {
        return $this->idpartenaire;
    }

    public function setIdpartenaire(?partenaire $idpartenaire): self
    {
        $this->idpartenaire = $idpartenaire;

        return $this;
    }

    /**
     * @return Collection|Depot[]
     */
    public function getDepots(): Collection
    {
        return $this->depots;
    }

    public function addDepot(Depot $depot): self
    {
        if (!$this->depots->contains($depot)) {
            $this->depots[] = $depot;
            $depot->setIdcompte($this);
        }

        return $this;
    }

    public function removeDepot(Depot $depot): self
    {
        if ($this->depots->contains($depot)) {
            $this->depots->removeElement($depot);
            // set the owning side to null (unless already changed)
            if ($depot->getIdcompte() === $this) {
                $depot->setIdcompte(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setIdcompte($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getIdcompte() === $this) {
                $user->setIdcompte(null);
            }
        }

        return $this;
    }
}
