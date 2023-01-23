<?php

namespace App\Entity;

use App\Repository\JoueurRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Mime\Message;
use Symfony\Component\Validator\Constraints\Length;

#[ORM\Entity(repositoryClass: JoueurRepository::class)]
class Joueur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    
    #[Assert\Length(min:4,max:15, minMessage:"Veuillez avoir au moins 4 caractères",maxMessage:"Veuillez avoir au max 10 caractères")]
    #[Assert\NotBlank]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    
    #[Assert\Email(
        message: 'The email {{ value }} is not a valid email.',
    )]
    #[Assert\NotBlank]
    private ?string $email = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $born_at = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    private ?int $score = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    private ?Game $Game = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

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

    public function getBornAt(): ?\DateTimeImmutable
    {
        return $this->born_at;
    }

    public function setBornAt(\DateTimeImmutable $born_at): self
    {
        $this->born_at = $born_at;

        return $this;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(int $score): self
    {
        $this->score = $score;

        return $this;
    }

    public function getGame(): ?Game
    {
        return $this->Game;
    }

    public function setGame(?Game $Game): self
    {
        $this->Game = $Game;

        return $this;
    }
}
