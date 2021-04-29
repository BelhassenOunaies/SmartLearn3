<?php

namespace App\Entity;

use App\Repository\ReclamationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReclamationRepository::class)
 */
class Reclamation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $poiId;

    /**
     * @ORM\Column(type="integer")
     */
    private $hId;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=500)
     */
    private $description;

    /**
     * @ORM\Column(type="date")
     */
    private $reclamationDate;

    /**
     * @ORM\Column(type="integer", options={"default" : 0})
     */
    private $resolu;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $reply;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPoiId(): ?int
    {
        return $this->poiId;
    }

    public function setPoiId(int $poiId): self
    {
        $this->poiId = $poiId;

        return $this;
    }

    public function getHId(): ?int
    {
        return $this->hId;
    }

    public function setHId(int $hId): self
    {
        $this->hId = $hId;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getReclamationDate(): ?\DateTimeInterface
    {
        return $this->reclamationDate;
    }

    public function setReclamationDate(\DateTimeInterface $reclamationDate): self
    {
        $this->reclamationDate = $reclamationDate;

        return $this;
    }

    public function getResolu(): ?int
    {
        return $this->resolu;
    }

    public function setResolu(int $resolu): self
    {
        $this->resolu = $resolu;

        return $this;
    }

    public function getReply(): ?string
    {
        return $this->reply;
    }

    public function setReply(?string $reply): self
    {
        $this->reply = $reply;

        return $this;
    }
}
