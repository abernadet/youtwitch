<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface, \Serializable
{

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=30)
     * @ORM\OneToMany(targetEntity="App\Entity\LostPassword", mappedBy="user")
     * @JoinColumn(name="user_id")
     * @Assert\NotBlank()
     * @Assert\Length(max=30, maxMessage="Le pseudo ne doit pas faire plus de 30 caractères")
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=191)
     * @Assert\NotBlank()
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=70)
     */
    private $password;

    /**
     * @param mixed
     * @Assert\Length(min=6, minMessage="Le mot de passe doit faire au moins 6 caractères", max=30, maxMessage="Le mot de passe ne doit pas faire plus de 30 caractères")
     */
    private $plainPassword;

    /**
     * @ORM\Column(type="array", name="roles")
     */
    private $roles;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @ORM\Column(type="string", nullable = true)
     * @Assert\Image(maxSize="1000k")
     */
    private $image;

    /**
     * @ORM\Column(type="string", length=255, name="twitchLogin", nullable = true)
     */
    private $twitchLogin;

    /**
     * //on indique a doctrine la relation oneToMany
     * @ORM\OneToMany(targetEntity="App\Entity\Tabo", mappedBy="user")
     * ceci ne va pas rajouter de champs dans la table
     */
    private $Tabo;

    /**
     * //on indique a doctrine la relation oneToMany
     * @ORM\OneToMany(targetEntity="App\Entity\Yabo", mappedBy="user")
     * ceci ne va pas rajouter de champs dans la table
     */
    private $Yabo;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     * @Assert\Regex("#0[1-9][0-9]{8}#")
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @ORM\Column(type="date")
     */
    private $birthdate;

    public function __construct()
    {
        $this->isActive = true; //par défaut, un user est actif
        $this->Tabo = new ArrayCollection();
        $this->Yabo = new ArrayCollection();

    }

    public function getId()
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getTwitchLogin(): ?string
    {
        return $this->twitchLogin;
    }

    public function setTwitchLogin(string $twitchLogin): self
    {
        $this->twitchLogin = $twitchLogin;

        return $this;
    }

    public function eraseCredentials(){
            $this->plainPassword = null;
    }

    public function getSalt(){
            return null;
    }

    public function serialize(){

        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            //see action on salt below
            //$this->salt,
        ));
}

    public function unserialize($serialized){

        list(
            $this->id,
            $this->username,
            $this->password,
            //see action on salt below
            //$this->salt,
            ) = \unserialize($serialized,['allowed_classes' => false]);
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    public function getRoles(): ?array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }
    public function getImage()
    {
        return $this->image;
    }
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection|Tabo[]
     */
    public function getTabo(): Collection{
        return $this->Tabo;
    }

    /**
     * @return Collection|Yabo[]
     */
    public function getYabo(): Collection{
        return $this->Yabo;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getBirthdate(): ?\DateTimeInterface
    {
        return $this->birthdate;
    }

    public function setBirthdate(\DateTimeInterface $birthdate): self
    {
        $this->birthdate = $birthdate;

        return $this;
    }
}
