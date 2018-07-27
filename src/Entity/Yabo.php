<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\YaboRepository")
 */
class Yabo
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
    private $idChannel;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * On va expliquer a doctrine que cette propriété fait référence à l'entité user et qu'il s'agit d'une relation manytoone
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="Yabo")
     */
    private $user;

    public function getId()
    {
        return $this->id;
    }

    public function getIdChannel(): ?string
    {
        return $this->idChannel;
    }

    public function setIdChannel(string $idChannel): self
    {
        $this->idChannel = $idChannel;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getUser() : ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;
        return $this;
    }
}
