<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[ORM\Table(
    name: 'repo_integration',
    uniqueConstraints: [new ORM\UniqueConstraint(name: 'uniq_repo_integration_provider_repo', columns: ['provider', 'repo_full_name'])],
    indexes: [new ORM\Index(name: 'idx_repo_integration_project_active', columns: ['project_id', 'is_active'])]
)]
class RepoIntegration
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid')]
    private ?Uuid $id = null;

    #[ORM\ManyToOne(inversedBy: 'repoIntegrations')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Project $project = null;

    #[ORM\Column(length: 32)]
    private string $provider;

    #[ORM\Column(length: 255)]
    private string $repoFullName;

    #[ORM\Column(length: 255)]
    private string $defaultBranch;

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $pipelineSelector = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $webhookSecret = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $credentialRef = null;

    #[ORM\Column(type: 'boolean')]
    private bool $isActive = true;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $updatedAt;

    #[ORM\OneToMany(mappedBy: 'integration', targetEntity: DeployJob::class, orphanRemoval: true)]
    private Collection $deployJobs;

    public function __construct()
    {
        $this->id = Uuid::v7();
        $now = new \DateTimeImmutable();
        $this->createdAt = $now;
        $this->updatedAt = $now;
        $this->deployJobs = new ArrayCollection();
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

    public function getProvider(): string
    {
        return $this->provider;
    }

    public function setProvider(string $provider): self
    {
        $this->provider = $provider;

        return $this;
    }

    public function getRepoFullName(): string
    {
        return $this->repoFullName;
    }

    public function setRepoFullName(string $repoFullName): self
    {
        $this->repoFullName = $repoFullName;

        return $this;
    }

    public function getDefaultBranch(): string
    {
        return $this->defaultBranch;
    }

    public function setDefaultBranch(string $defaultBranch): self
    {
        $this->defaultBranch = $defaultBranch;

        return $this;
    }

    public function getPipelineSelector(): ?array
    {
        return $this->pipelineSelector;
    }

    public function setPipelineSelector(?array $pipelineSelector): self
    {
        $this->pipelineSelector = $pipelineSelector;

        return $this;
    }

    public function getWebhookSecret(): ?string
    {
        return $this->webhookSecret;
    }

    public function setWebhookSecret(?string $webhookSecret): self
    {
        $this->webhookSecret = $webhookSecret;

        return $this;
    }

    public function getCredentialRef(): ?string
    {
        return $this->credentialRef;
    }

    public function setCredentialRef(?string $credentialRef): self
    {
        $this->credentialRef = $credentialRef;

        return $this;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

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
     * @return Collection<int, DeployJob>
     */
    public function getDeployJobs(): Collection
    {
        return $this->deployJobs;
    }

    public function addDeployJob(DeployJob $deployJob): self
    {
        if (!$this->deployJobs->contains($deployJob)) {
            $this->deployJobs->add($deployJob);
            $deployJob->setIntegration($this);
        }

        return $this;
    }

    public function removeDeployJob(DeployJob $deployJob): self
    {
        if ($this->deployJobs->removeElement($deployJob)) {
            if ($deployJob->getIntegration() === $this) {
                $deployJob->setIntegration(null);
            }
        }

        return $this;
    }
}
