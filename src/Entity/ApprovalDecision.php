<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[ORM\Table(
    name: 'approval_decision',
    uniqueConstraints: [new ORM\UniqueConstraint(name: 'uniq_approval_decision_request_user', columns: ['approval_request_id', 'user_id'])]
)]
class ApprovalDecision
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid')]
    private ?Uuid $id = null;

    #[ORM\ManyToOne(inversedBy: 'approvalDecisions')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?ApprovalRequest $approvalRequest = null;

    #[ORM\ManyToOne(inversedBy: 'approvalDecisions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(length: 32)]
    private string $decision;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $note = null;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $decidedAt;

    public function __construct()
    {
        $this->id = Uuid::v7();
        $this->decidedAt = new \DateTimeImmutable();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getApprovalRequest(): ?ApprovalRequest
    {
        return $this->approvalRequest;
    }

    public function setApprovalRequest(?ApprovalRequest $approvalRequest): self
    {
        $this->approvalRequest = $approvalRequest;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getDecision(): string
    {
        return $this->decision;
    }

    public function setDecision(string $decision): self
    {
        $this->decision = $decision;

        return $this;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getDecidedAt(): \DateTimeImmutable
    {
        return $this->decidedAt;
    }

    public function setDecidedAt(\DateTimeImmutable $decidedAt): self
    {
        $this->decidedAt = $decidedAt;

        return $this;
    }
}
