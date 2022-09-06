<?php

namespace App\Entity;

use App\Repository\VenteRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Cascade;

#[ORM\Entity(repositoryClass: VenteRepository::class)]
class Vente
{
    const Methods = [
    "espèce" => "Espece",
    "par chèque" => "Cheque",
    "par carte bancaire"=>"Carte"
];
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column]
    private ?int $qte_c = null;

    #[ORM\Column()]
    private ?string $mode_paiement = null;

    #[ORM\ManyToOne(inversedBy: 'ventes')]
    #[ORM\JoinColumn(onDelete: "CASCADE")]
    private Article $article ;

    #[ORM\Column]
    private ?bool $avoir = false;

    #[ORM\Column]
    private ?float $Total = null;

    #[ORM\Column]
    private ?float $prix_vente = null;

    public function __construct()
    {
        $this->date = new \DateTime('today');
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getQteC(): ?int
    {
        return $this->qte_c;
    }

    public function setQteC(int $qte_c): self
    {
        $this->qte_c = $qte_c;

        return $this;
    }

    public function getModePaiement(): string
    {
        return $this->mode_paiement;
    }

    public function setModePaiement(string $mode_paiement): self
    {
        $this->mode_paiement = $mode_paiement;

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

    public function __toString() : string {
        return $this->id;
}

    public function isAvoir(): ?bool
    {
        return $this->avoir;
    }

    public function setAvoir(bool $avoir): self
    {
        $this->avoir = $avoir;

        return $this;
    }

    public function getTotal(): ?float
    {
        return $this->Total;
    }

    public function setTotal(float $Total): self
    {
        $this->Total = $Total;

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
