<?php

namespace App\Entity;

use App\Repository\TreatmentRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Patch;
use Symfony\Component\Serializer\Attribute\Groups;

#[ApiResource(
    operations: [
        new GetCollection(
            security: "is_granted('ROLE_ASSISTANT')"
        ),
        new Post(
            security: "is_granted('ROLE_VETERINARIAN')",
            securityMessage: "Seuls les vétérinaires peuvent créer un traitement."
        ),
        new Get(
            security: "is_granted('ROLE_ASSISTANT')"
        ),
        new Put(
            security: "is_granted('ROLE_VETERINARIAN')",
            securityMessage: "Seuls les vétérinaires peuvent modifier un traitement."
        ),
        new Patch(
            security: "is_granted('ROLE_VETERINARIAN')",
            securityMessage: "Seuls les vétérinaires peuvent modifier un traitement."
        ),
        new Delete(
            security: "is_granted('ROLE_VETERINARIAN')",
            securityMessage: "Seuls les vétérinaires peuvent supprimer un traitement."
        ),
    ],
    normalizationContext: ['groups' => ['read']],
    denormalizationContext: ['groups' => ['write']],
)]
#[ORM\Entity(repositoryClass: TreatmentRepository::class)]
class Treatment
{
    #[Groups('read')]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(['read', 'write'])]
    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[Groups(['read', 'write'])]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[Groups(['read', 'write'])]
    #[ORM\Column]
    private ?int $price = null;

    #[Groups(['read', 'write'])]
    #[ORM\Column(type: 'time')]
    private ?\DateTimeInterface $duration = null;

    #[ORM\ManyToMany(targetEntity: Consultation::class, mappedBy: 'traitements')]
    private Collection $consultations;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getDuration(): ?\DateTimeInterface
    {
        return $this->duration;
    }

    public function setDuration(\DateTimeInterface $duration): static
    {
        $this->duration = $duration;

        return $this;
    }
}
