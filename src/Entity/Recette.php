<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Cocur\Slugify\Slugify;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecetteRepository")
 * @UniqueEntity("titre")
 */
class Recette
{
    const RECETTE_TYPE = 
    [
        0 => 'entrée',
        1 => 'plat principal',
        2 => 'dessert'
    ];

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $idAuth;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min=5, max=255)
     */
    private $titre;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\Range(min=1)
     */
    private $nbrPers;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\Range(min=0, max=2)
     */
    private $type;

    /**
     * @ORM\Column(type="time")
     * @Assert\Time
     * @var string A "H:i:s" formatted value
     */
    private $tempsPrepa;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $image;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdAuth(): ?int
    {
        return $this->idAuth;
    }

    public function setIdAuth(int $id_auth): self
    {
        $this->idAuth = $id_auth;

        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getSlug(): string
    {
        return (new Slugify())->slugify($this->titre);
    }

    public function getNbrPers(): ?int
    {
        return $this->nbrPers;
    }

    public function setNbrPers(int $nbr_pers): self
    {
        $this->nbrPers = $nbr_pers;

        return $this;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getRecetteType(): string
    {
        return self::RECETTE_TYPE[$this->type];
    }

    public function getTempsPrepa(): ?\DateTimeInterface
    {
        return $this->tempsPrepa;
    }

    public function setTempsPrepa(\DateTimeInterface $temps_prepa): self
    {
        $this->tempsPrepa = $temps_prepa;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->createdAt = $created_at;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }
}
