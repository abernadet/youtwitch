<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TrendingSearchRepository")
 */
class TrendingSearch
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $idYoutube;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Name;

    /**
     * @ORM\Column(type="integer")
     */
    private $NumberSearch;

    public function getId()
    {
        return $this->id;
    }

    public function getIdYoutube(): ?string
    {
        return $this->idYoutube;
    }

    public function setIdYoutube(string $idYoutube): self
    {
        $this->idYoutube = $idYoutube;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;

        return $this;
    }

    public function getNumberSearch(): ?int
    {
        return $this->NumberSearch;
    }

    public function setNumberSearch(int $NumberSearch): self
    {
        $this->NumberSearch = $NumberSearch;

        return $this;
    }
}
