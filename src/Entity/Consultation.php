<?php

namespace App\Entity;

use App\Enum\StatusEnum;
use App\Repository\ConsultationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use Symfony\Component\Serializer\Attribute\Groups;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;

#[ApiResource(
    operations: [
        new GetCollection(
            security: "is_granted('ROLE_ASSISTANT')",
            securityMessage: "Seul le personnel peut voir la liste des rendez-vous."
        ),
        new Post(
            security: "is_granted('ROLE_ASSISTANT')",
            securityMessage: "Seuls les assistants peuvent créer des rendez-vous."
        ),
        new Get(
            security: "is_granted('ROLE_ASSISTANT')",
            securityMessage: "Seul le personnel peut voir les détails d'un rendez-vous."
        ),
        new Put(
            security: "is_granted('ROLE_ASSISTANT') and object.getStatus() != 'terminé'",
            securityMessage: "Seuls les assistants peuvent modifier un rendez-vous non terminé."
        ),
        new Patch(
            security: "is_granted('ROLE_VETERINARIAN')",
            securityMessage: "Seuls les vétérinaires peuvent modifier le statut d'un rendez-vous."
        ),
        new Delete(
            security: "is_granted('ROLE_DIRECTOR')",
            securityMessage: "Seul le directeur peut supprimer un rendez-vous."
        ),
    ],
    normalizationContext: ['groups' => ['read']],
    denormalizationContext: ['groups' => ['write']],
)]
#[ORM\Entity(repositoryClass: ConsultationRepository::class)]
class Consultation
{
    #[Groups('read')]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups('read')]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdDate = null;

    #[Groups(['read', 'write'])]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $consultationDate = null;

    #[Groups(['read', 'write'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $motif = null;

    #[Groups(['read', 'write'])]
    #[ORM\ManyToOne(inversedBy: 'consultations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Animal $animal = null;

    #[Groups(['read', 'write'])]
    #[ORM\ManyToOne(inversedBy: 'consultations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $assistant = null;

    #[Groups(['read', 'write'])]
    #[ORM\ManyToOne(inversedBy: 'consultations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $veterinaire = null;

    #[Groups(['read', 'write'])]
    #[ORM\Column(enumType: StatusEnum::class)]
    private ?StatusEnum $status = null;

    /**
     * @var Collection<int, Media>
     */
    #[ORM\ManyToMany(targetEntity: Treatment::class, inversedBy: 'consultations')]
    private Collection $traitements;

    public function __construct()
    {
        $this->traitements = new ArrayCollection();
        $this->createdDate = new \DateTime("now", new \DateTimeZone("Europe/Paris"));
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedDate(): ?\DateTimeInterface
    {
        return $this->createdDate;
    }

    public function setCreatedDate(\DateTimeInterface $createdDate): static
    {
        $this->createdDate = $createdDate;

        return $this;
    }

    public function getConsultationDate(): ?\DateTimeInterface
    {
        return $this->consultationDate;
    }

    public function setConsultationDate(\DateTimeInterface $consultationDate): static
    {
        $this->consultationDate = $consultationDate;

        return $this;
    }

    public function getMotif(): ?string
    {
        return $this->motif;
    }

    public function setMotif(?string $motif): static
    {
        $this->motif = $motif;

        return $this;
    }

    public function getAnimal(): ?Animal
    {
        return $this->animal;
    }

    public function setAnimal(?Animal $animal): static
    {
        $this->animal = $animal;

        return $this;
    }

    public function getAssistant(): ?User
    {
        return $this->assistant;
    }

    public function setAssistant(?User $assistant): static
    {
        $this->assistant = $assistant;

        return $this;
    }

    public function getVeterinaire(): ?User
    {
        return $this->veterinaire;
    }

    public function setVeterinaire(?User $veterinaire): static
    {
        $this->veterinaire = $veterinaire;

        return $this;
    }

    public function getStatus(): ?StatusEnum
    {
        return $this->status;
    }

    public function setStatus(StatusEnum $status): static
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection<int, Media>
     */
    public function getTraitements(): Collection
    {
        return $this->traitements;
    }

    public function addTraitement(Media $traitement): static
    {
        if (!$this->traitements->contains($traitement)) {
            $this->traitements->add($traitement);
        }

        return $this;
    }

    public function removeTraitement(Media $traitement): static
    {
        $this->traitements->removeElement($traitement);

        return $this;
    }
}
