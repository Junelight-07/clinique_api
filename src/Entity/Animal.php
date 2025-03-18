<?php

namespace App\Entity;

use App\Repository\AnimalRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use Symfony\Component\Serializer\Attribute\Groups;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;

#[ApiResource(
    forceEager: false,
    normalizationContext: ['groups' => ['read']],
    denormalizationContext: ['groups' => ['write']],
)]
#[ORM\Entity(repositoryClass: AnimalRepository::class)]
#[ApiFilter(SearchFilter::class, properties: ['id' => 'exact', 'title' => 'partial', 'content' => 'partial'])]
#[ApiFilter(OrderFilter::class, properties: ['id', 'createdDate'], arguments: ['orderParameterName' => 'order'])]
class Animal
{
    #[Groups('read')]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(['read', 'write'])]
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[Groups(['read', 'write'])]
    #[ORM\Column(length: 255)]
    private ?string $species = null;

    #[Groups(['read', 'write'])]
    #[ORM\Column(length: 255)]
    private ?string $birthDate = null;

    #[Groups(['read', 'write'])]
    #[ORM\ManyToOne(inversedBy: 'animal')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Owner $owner = null;

    #[ORM\OneToOne(inversedBy: 'animal', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: true)]
    private ?Media $picture = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getSpecies(): ?string
    {
        return $this->species;
    }

    public function setSpecies(string $species): static
    {
        $this->species = $species;

        return $this;
    }

    public function getBirthDate(): ?string
    {
        return $this->birthDate;
    }

    public function setBirthDate(string $birthDate): static
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    public function getPicture(): ?Media
    {
        return $this->picture;
    }

    public function setPicture(?Media $picture): static
    {
        $this->picture = $picture;

        return $this;
    }

    public function getOwner(): ?Owner
    {
        return $this->owner;
    }

    public function setOwner(?Owner $owner): static
    {
        $this->owner = $owner;

        return $this;
    }

    public function setMedia(?Media $media): static
    {
        // unset the owning side of the relation if necessary
        if ($media === null && $this->media !== null) {
            $this->media->setAnimal(null);
        }

        // set the owning side of the relation if necessary
        if ($media !== null && $media->getAnimal() !== $this) {
            $media->setAnimal($this);
        }

        $this->media = $media;

        return $this;
    }
}
