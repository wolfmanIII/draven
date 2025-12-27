<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[ORM\Table(
    name: 'deploy_job',
    indexes: [
        new ORM\Index(name: 'idx_deploy_job_status', columns: ['status']),
        new ORM\Index(name: 'idx_deploy_job_env_requested_at', columns: ['environment_id', 'requested_at']),
        new ORM\Index(name: 'idx_deploy_job_project_requested_at', columns: ['project_id', 'requested_at'])
    ]
)]
class DeployJob
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid')]
    private ?Uuid $id = null;

    #[ORM\ManyToOne(inversedBy: 'deployJobs')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Project $project = null;

    #[ORM\ManyToOne(inversedBy: 'deployJobs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Environment $environment = null;

    #[ORM\ManyToOne(inversedBy: 'deployJobs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?RepoIntegration $integration = null;

    #[ORM\Column(length: 32)]
    private string $type;

    #[ORM\Column(length: 32)]
    private string $refType;

    #[ORM\Column(length: 255)]
    private string $refName;

    #[ORM\Column(length: 64, nullable: true)]
    private ?string $commitSha = null;

    #[ORM\ManyToOne(inversedBy: 'deployJobsRequested')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $requestedBy = null;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $requestedAt;

    #[ORM\Column(length: 32)]
    private string $status;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $lockedAt = null;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $startedAt = null;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $finishedAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $externalRunId = null;

    #[ORM\Column(length: 1024, nullable: true)]
    private ?string $externalRunUrl = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $summary = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $errorMessage = null;

    #[ORM\OneToOne(mappedBy: 'job', targetEntity: ApprovalRequest::class, cascade: ['persist', 'remove'])]
    private ?ApprovalRequest $approvalRequest = null;

    #[ORM\OneToMany(mappedBy: 'job', targetEntity: DeployLogLine::class, orphanRemoval: true)]
    private Collection $logLines;

    public function __construct()
    {
        $this->id = Uuid::v7();
        $this->requestedAt = new \DateTimeImmutable();
        $this->logLines = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): self
    {
        $this->project = $project;

        return $this;
    }

    public function getEnvironment(): ?Environment
    {
        return $this->environment;
    }

    public function setEnvironment(?Environment $environment): self
    {
        $this->environment = $environment;

        return $this;
    }

    public function getIntegration(): ?RepoIntegration
    {
        return $this->integration;
    }

    public function setIntegration(?RepoIntegration $integration): self
    {
        $this->integration = $integration;

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getRefType(): string
    {
        return $this->refType;
    }

    public function setRefType(string $refType): self
    {
        $this->refType = $refType;

        return $this;
    }

    public function getRefName(): string
    {
        return $this->refName;
    }

    public function setRefName(string $refName): self
    {
        $this->refName = $refName;

        return $this;
    }

    public function getCommitSha(): ?string
    {
        return $this->commitSha;
    }

    public function setCommitSha(?string $commitSha): self
    {
        $this->commitSha = $commitSha;

        return $this;
    }

    public function getRequestedBy(): ?User
    {
        return $this->requestedBy;
    }

    public function setRequestedBy(?User $requestedBy): self
    {
        $this->requestedBy = $requestedBy;

        return $this;
    }

    public function getRequestedAt(): \DateTimeImmutable
    {
        return $this->requestedAt;
    }

    public function setRequestedAt(\DateTimeImmutable $requestedAt): self
    {
        $this->requestedAt = $requestedAt;

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

    public function getLockedAt(): ?\DateTimeImmutable
    {
        return $this->lockedAt;
    }

    public function setLockedAt(?\DateTimeImmutable $lockedAt): self
    {
        $this->lockedAt = $lockedAt;

        return $this;
    }

    public function getStartedAt(): ?\DateTimeImmutable
    {
        return $this->startedAt;
    }

    public function setStartedAt(?\DateTimeImmutable $startedAt): self
    {
        $this->startedAt = $startedAt;

        return $this;
    }

    public function getFinishedAt(): ?\DateTimeImmutable
    {
        return $this->finishedAt;
    }

    public function setFinishedAt(?\DateTimeImmutable $finishedAt): self
    {
        $this->finishedAt = $finishedAt;

        return $this;
    }

    public function getExternalRunId(): ?string
    {
        return $this->externalRunId;
    }

    public function setExternalRunId(?string $externalRunId): self
    {
        $this->externalRunId = $externalRunId;

        return $this;
    }

    public function getExternalRunUrl(): ?string
    {
        return $this->externalRunUrl;
    }

    public function setExternalRunUrl(?string $externalRunUrl): self
    {
        $this->externalRunUrl = $externalRunUrl;

        return $this;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(?string $summary): self
    {
        $this->summary = $summary;

        return $this;
    }

    public function getErrorMessage(): ?string
    {
        return $this->errorMessage;
    }

    public function setErrorMessage(?string $errorMessage): self
    {
        $this->errorMessage = $errorMessage;

        return $this;
    }

    public function getApprovalRequest(): ?ApprovalRequest
    {
        return $this->approvalRequest;
    }

    public function setApprovalRequest(?ApprovalRequest $approvalRequest): self
    {
        if ($approvalRequest && $approvalRequest->getJob() !== $this) {
            $approvalRequest->setJob($this);
        }

        $this->approvalRequest = $approvalRequest;

        return $this;
    }

    /**
     * @return Collection<int, DeployLogLine>
     */
    public function getLogLines(): Collection
    {
        return $this->logLines;
    }

    public function addLogLine(DeployLogLine $logLine): self
    {
        if (!$this->logLines->contains($logLine)) {
            $this->logLines->add($logLine);
            $logLine->setJob($this);
        }

        return $this;
    }

    public function removeLogLine(DeployLogLine $logLine): self
    {
        if ($this->logLines->removeElement($logLine)) {
            if ($logLine->getJob() === $this) {
                $logLine->setJob(null);
            }
        }

        return $this;
    }
}
