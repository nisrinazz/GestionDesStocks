<?php

namespace App\Entity;

use App\Repository\EntreeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EntreeRepository::class)]
class Entree
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom_fournisseur = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;


    #[ORM\Column]
    private ?int $qte_a = null;

    #[ORM\ManyToOne(inversedBy: 'entrees')]
    #[ORM\JoinColumn(onDelete: "CASCADE")]
    private ?Article $article = null;

    #[ORM\Column]
    private ?float $prix_achat = null;

    #[ORM\Column]
    private ?float $prix_vente = null;

    public function __construct()
    { $this->date = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomFournisseur(): ?string
    {
        return $this->nom_fournisseur;
    }

    public function setNomFournisseur(string $nom_fournisseur): self
    {
        $this->nom_fournisseur = $nom_fournisseur;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getQteA(): ?int
    {
        return $this->qte_a;
    }

    public function setQteA(int $qte_a): self
    {
        $this->qte_a = $qte_a;

        return $this;
    }

    public function getArticle(): ?Article
    {
        return $this->article;
    }

    public function setArticle(?Article $article): self
    {
        $this->article = $article;

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
}
