<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass="App\Repository\ChatRepository")
 */
class Chat
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=30)
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="user")
     * @ORM\JoinColumn(name="user_id")
     * @Assert\NotBlank()
     * @Assert\Length(max=30, maxMessage="Le pseudo ne doit pas faire plus de 30 caractères")
     */

    private $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Message", mappedBy="user")
     */
    private $Message;

    public function getId()
    {
        return $this->id;
    }

    public function getUser(): ?string
    {
        return $this->user;
    }

    public function setUser(string $User): self
    {
        $this->user = $User;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->Message;
    }

    public function setMessage(string $Message): self
    {
        $this->Message = $Message;

        return $this;
    }
}
