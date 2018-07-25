<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\User;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MessageRepository")
 */
class Message
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text", length=255)
     */
    private $contenu;


    /**
     * @ORM\Column(type="datetime")
     */
    private $dateenvoi;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="message")
     */
    private $user;

    public function getId()
    {
        return $this->id;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): self
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getDateenvoi(): ?\DateTimeInterface
    {
        return $this->dateenvoi;
    }

    public function setDateenvoi(\DateTimeInterface $dateenvoi): self
    {
        $this->dateenvoi = $dateenvoi;

        return $this;
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
}
