<?php

namespace App\Entity;

use App\Repository\SimpleRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=SimpleRepository::class)
 */
class Simple
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id_user;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="Ce champs est obligatoire")<
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Ce champs est obligatoire")
     *@Assert\Length(
     *min=6,
     *max=50,
     *minMessage="Le mot de passe doit comporter au moins {{ limit }} caractères",
     *maxMessage ="Le mot de passe doit comporter au plus {{ limit }} caractères"
     *
    )
     */

    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Ce champs est obligatoire")
     * @Assert\Email(message="Cette adresse mail n'est pas valide ")
     */
    private $mail;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank(message="Ce champs est obligatoire")
     */
    private $date_n;

    public function getIdUser(): ?int
    {
        return $this->id_user;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): self
    {
        $this->username = $username;

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

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getDateN(): ?\DateTimeInterface
    {
        return $this->date_n;
    }

    public function setDateN(\DateTimeInterface $date_n): self
    {
        $this->date_n = $date_n;

        return $this;
    }
}
