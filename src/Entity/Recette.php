<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="recettes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\Range(min=1)
     */
    private $nbrPers;

    /**
     * @ORM\Column(type="time")
     * @Assert\Time
     * @var string A "H:i:s" formatted value
     */
    private $tempsPrepa;

    /**
     * @ORM\Column(type="time")
     * @Assert\Time
     * @var string A "H:i:s" formatted value
     */
    private $tempsCuisson;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min=5, max=255)
     */
    private $titre;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\Range(min=0, max=2)
     */
    private $type;

    /**
     * @ORM\Column(type="text")
     */
    private $image;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $modifiedAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Ingredient", mappedBy="recette", orphanRemoval=true, cascade={"persist"})
     */
    private $ingredients;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Etape", mappedBy="recette", orphanRemoval=true, cascade={"persist"})
     */
    private $etapes;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->modifiedAt = new \DateTime();
        $this->ingredients = new ArrayCollection();
        $this->etapes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getNbrPers(): ?int
    {
        return $this->nbrPers;
    }

    public function setNbrPers(int $nbrPers): self
    {
        $this->nbrPers = $nbrPers;

        return $this;
    }

    public function getTempsPrepa(): ?\DateTimeInterface
    {
        return $this->tempsPrepa;
    }

    public function setTempsPrepa(\DateTimeInterface $tempsPrepa): self
    {
        $this->tempsPrepa = $tempsPrepa;

        return $this;
    }

    public function getTempsCuisson(): ?\DateTimeInterface
    {
        return $this->tempsCuisson;
    }

    public function setTempsCuisson(\DateTimeInterface $tempsCuisson): self
    {
        $this->tempsCuisson = $tempsCuisson;

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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getModifiedAt(): ?\DateTimeInterface
    {
        return $this->modifiedAt;
    }

    public function setModifiedAt(?\DateTimeInterface $modifiedAt): self
    {
        $this->modifiedAt = $modifiedAt;

        return $this;
    }

    /**
     * @return Collection|Ingredient[]
     */
    public function getIngredients(): Collection
    {
        return $this->ingredients;
    }

    public function addIngredient(Ingredient $ingredient): self
    {
        if (!$this->ingredients->contains($ingredient)) {
            $this->ingredients[] = $ingredient;
            $ingredient->setRecette($this);
        }

        return $this;
    }

    public function removeIngredient(Ingredient $ingredient): self
    {
        if ($this->ingredients->contains($ingredient)) {
            $this->ingredients->removeElement($ingredient);
            // set the owning side to null (unless already changed)
            if ($ingredient->getRecette() === $this) {
                $ingredient->setRecette(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Etape[]
     */
    public function getEtapes(): Collection
    {
        return $this->etapes;
    }

    public function addEtape(Etape $etape): self
    {
        if (!$this->etapes->contains($etape)) {
            $this->etapes[] = $etape;
            $etape->setRecette($this);
        }

        return $this;
    }

    public function removeEtape(Etape $etape): self
    {
        if ($this->etapes->contains($etape)) {
            $this->etapes->removeElement($etape);
            // set the owning side to null (unless already changed)
            if ($etape->getRecette() === $this) {
                $etape->setRecette(null);
            }
        }

        return $this;
    }
}
