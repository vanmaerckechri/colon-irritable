<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\IngredientRecetteRepository")
 */
class IngredientRecette
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $id_recette;

    /**
     * @ORM\Column(type="integer")
     */
    private $id_ingredient;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdRecette(): ?int
    {
        return $this->id_recette;
    }

    public function setIdRecette(int $id_recette): self
    {
        $this->id_recette = $id_recette;

        return $this;
    }

    public function getIdIngredient(): ?int
    {
        return $this->id_ingredient;
    }

    public function setIdIngredient(int $id_ingredient): self
    {
        $this->id_ingredient = $id_ingredient;

        return $this;
    }
}
