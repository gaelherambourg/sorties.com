<?php

namespace App\Entity;

use App\Repository\LieuRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=LieuRepository::class)
 */
class Lieu
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank(message="Veuillez donnez un nom au lieu")
     * @Assert\Length(min=2, minMessage="Le nom du lieu doit comporter au moins 2 caractères", max=30, maxMessage="Le nom du lieu ne doit pas comporter plus de 30 caractères")
     * @ORM\Column(type="string", length=30)
     */
    private $nom;

    /**
     * @Assert\Length(max=30, maxMessage="Le nom du lieu ne doit pas comporter plus de 30 caractères")
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $rue;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $latitude;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $longitude;

    /**
     * @ORM\ManyToOne(targetEntity=Ville::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $villes_no_ville;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getRue(): ?string
    {
        return $this->rue;
    }

    public function setRue(?string $rue): self
    {
        $this->rue = $rue;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(?float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(?float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getVillesNoVille(): ?Ville
    {
        return $this->villes_no_ville;
    }

    public function setVillesNoVille(?Ville $villes_no_ville): self
    {
        $this->villes_no_ville = $villes_no_ville;

        return $this;
    }

    public function __toString()
    {
        return $this->nom;
    }
}
