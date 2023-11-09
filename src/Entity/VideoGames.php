<?php

namespace App\Entity;

use App\Repository\VideoGamesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VideoGamesRepository::class)]
class VideoGames
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $platform = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imgGame = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $releaseDate = null;

    #[ORM\Column]
    private ?int $idGameAPI = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): static
    {
        $this->Name = $Name;

        return $this;
    }

    public function getPlatform(): ?string
    {
        return $this->platform;
    }

    public function setPlatform(?string $platform): static
    {
        $this->platform = $platform;

        return $this;
    }

    public function getImgGame(): ?string
    {
        return $this->imgGame;
    }

    public function setImgGame(?string $imgGame): static
    {
        $this->imgGame = $imgGame;

        return $this;
    }

    public function getReleaseDate(): ?string
    {
        return $this->releaseDate;
    }

    public function setReleaseDate(?string $releaseDate): static
    {
        $this->releaseDate = $releaseDate;

        return $this;
    }

    public function getIdGameAPI(): ?int
    {
        return $this->idGameAPI;
    }

    public function setIdGameAPI(int $idGameAPI): static
    {
        $this->idGameAPI = $idGameAPI;

        return $this;
    }
}
