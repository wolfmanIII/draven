<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[ORM\Table(
    name: 'approval_request',
    uniqueConstraints: [new ORM\UniqueConstraint(name: 'uniq_approval_request_job', columns: ['job_id'])]
)]
class ApprovalRequest
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid')]
    private ?Uuid $id = null;

    #[ORM\OneToOne(inversedBy: 'approvalRequest', targetEntity: DeployJob::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?DeployJob $job = null;

    #[ORM\Column(type: 'integer')]
    private int $requiredCount;

    #[ORM\Column(length: 32)]
    private string $status;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $updatedAt;

    #[ORM\OneToMany(mappedBy: 'approvalRequest', targetEntity: ApprovalDecision::class, orphanRemoval: true)]
    private Collection $decisions;

    public function __construct()
    {
        $this->id = Uuid::v7();
        $now = new \DateTimeImmutable();
        $this->createdAt = $now;
        $this->updatedAt = $now;
        $this->decisions = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getJob(): ?DeployJob
    {
        return $this->job;
    }

    public function setJob(?DeployJob $job): self
    {
        $this->job = $job;

        return $this;
    }

    public function getRequiredCount(): int
    {
        return $this->requiredCount;
    }

    public function setRequiredCount(int $requiredCount): self
    {
        $this->requiredCount = $requiredCount;

        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection<int, ApprovalDecision>
     */
    public function getDecisions(): Collection
    {
        return $this->decisions;
    }

    public function addDecision(ApprovalDecision $decision): self
    {
        if (!$this->decisions->contains($decision)) {
            $this->decisions->add($decision);
            $decision->setApprovalRequest($this);
        }

        return $this;
    }

    public function removeDecision(ApprovalDecision $decision): self
    {
        if ($this->decisions->removeElement($decision)) {
            if ($decision->getApprovalRequest() === $this) {
                $decision->setApprovalRequest(null);
            }
        }

        return $this;
    }
}
