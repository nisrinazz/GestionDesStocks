<?php

namespace App\Entity;

use App\Repository\AvoirRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AvoirRepository::class)]
class Avoir
{   const Methods=[
    ' par espèce'=>'Espece',
    'par chèque'=>'Cheque',
    'par versement'=>'Versement',
    'échange'=>'Echange'];
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255)]
    private ?string $methode_remboursement = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(onDelete: "CASCADE")]
    private ?Vente $vente = null;

    public function __construct(){
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

    public function getMethodeRemboursement(): ?string
    {
        return $this->methode_remboursement;
    }

    public function setMethodeRemboursement(string $methode_remboursement): self
    {
        $this->methode_remboursement = $methode_remboursement;

        return $this;
    }

    public function getVente(): ?Vente
    {
        return $this->vente;
    }

    public function setVente(?Vente $vente): self
    {
        $this->vente = $vente;

        return $this;
    }


}
