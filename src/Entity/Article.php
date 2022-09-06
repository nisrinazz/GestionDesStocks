<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


#[ORM\Entity(repositoryClass: ArticleRepository::class)]
#[UniqueEntity(fields: "Ref",message: "Référence déjà utilisée")]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Ref = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column]
    private ?float $prix_achat = null;

    #[ORM\Column]
    private ?float $prix_vente = null;

    #[ORM\Column]
    private ?int $qte = null;

    #[ORM\OneToMany(mappedBy: 'article', targetEntity: Vente::class)]
    private Collection $ventes;

    #[ORM\OneToMany(mappedBy: 'article', targetEntity: Entree::class)]
    private Collection $entrees;

    public function __construct()
    {
        $this->ventes = new ArrayCollection();
        $this->entrees = new ArrayCollection();
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRef(): ?string
    {
        return $this->Ref;
    }

    public function setRef(string $Ref): self
    {
        $this->Ref = $Ref;

        return $this;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrixAchat(): ?float
    {
        return $this->prix_achat;
    }

    public function setPrixAchat(float $prix_achat): self
    {
        $this->prix_achat = $prix_achat;

        return $this;
    }

    public function getPrixVente(): ?float
    {
        return $this->prix_vente;
    }

    public function setPrixVente(float $prix_vente): self
    {
        $this->prix_vente = $prix_vente;

        return $this;
    }

    public function getQte(): ?int
    {
        return $this->qte;
    }

    public function setQte(int $qte): self
    {
        $this->qte = $qte;

        return $this;
    }

    /**
     * @return Collection<int, Vente>
     */
    public function getVentes(): Collection
    {
        return $this->ventes;
    }

    public function addVente(Vente $vente): self
    {
        if (!$this->ventes->contains($vente)) {
            $this->ventes->add($vente);
            $vente->setArticle($this);
        }

        return $this;
    }

    public function removeVente(Vente $vente): self
    {
        if ($this->ventes->removeElement($vente)) {
            // set the owning side to null (unless already changed)
            if ($vente->getArticle() === $this) {
                $vente->setArticle(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->libelle;
    }

    public function countVente() {
        $vente=$this->ventes;
        $total=0;
        foreach($vente as $v)  {
            $total=$total + $v->getQteC() ;
        }
        return $total ;
    }
    /**
     * @return Collection<int, Entree>
     */
    public function getEntrees(): Collection
    {
        return $this->entrees;
    }

    public function addEntree(Entree $entree): self
    {
        if (!$this->entrees->contains($entree)) {
            $this->entrees->add($entree);
            $entree->setArticle($this);
        }

        return $this;
    }

    public function removeEntree(Entree $entree): self
    {
        if ($this->entrees->removeElement($entree)) {
            // set the owning side to null (unless already changed)
            if ($entree->getArticle() === $this) {
                $entree->setArticle(null);
            }
        }

        return $this;
    }


}
