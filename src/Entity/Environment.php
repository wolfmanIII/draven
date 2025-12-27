<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[ORM\Table(
    name: 'environment',
    uniqueConstraints: [new ORM\UniqueConstraint(name: 'uniq_environment_project_name', columns: ['project_id', 'name'])]
)]
class Environment
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid')]
    private ?Uuid $id = null;

    #[ORM\ManyToOne(inversedBy: 'environments')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Project $project = null;

    #[ORM\Column(length: 32)]
    private string $name;

    #[ORM\ManyToOne(inversedBy: 'environments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Policy $policy = null;

    #[ORM\Column(type: 'boolean')]
    private bool $isEnabled = true;

    #[ORM\Column(length: 32)]
    private string $lockStrategy = 'exclusive';

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $updatedAt;

    #[ORM\OneToOne(mappedBy: 'environment', targetEntity: EnvironmentLock::class, cascade: ['persist', 'remove'])]
    private ?EnvironmentLock $lock = null;

    #[ORM\OneToMany(mappedBy: 'environment', targetEntity: DeployJob::class, orphanRemoval: true)]
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

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPolicy(): ?Policy
    {
        return $this->policy;
    }

    public function setPolicy(?Policy $policy): self
    {
        $this->policy = $policy;

        return $this;
    }

    public function isEnabled(): bool
    {
        return $this->isEnabled;
    }

    public function setIsEnabled(bool $isEnabled): self
    {
        $this->isEnabled = $isEnabled;

        return $this;
    }

    public function getLockStrategy(): string
    {
        return $this->lockStrategy;
    }

    public function setLockStrategy(string $lockStrategy): self
    {
        $this->lockStrategy = $lockStrategy;

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

    public function getLock(): ?EnvironmentLock
    {
        return $this->lock;
    }

    public function setLock(?EnvironmentLock $lock): self
    {
        if ($lock && $lock->getEnvironment() !== $this) {
            $lock->setEnvironment($this);
        }

        $this->lock = $lock;

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
            $deployJob->setEnvironment($this);
        }

        return $this;
    }

    public function removeDeployJob(DeployJob $deployJob): self
    {
        if ($this->deployJobs->removeElement($deployJob)) {
            if ($deployJob->getEnvironment() === $this) {
                $deployJob->setEnvironment(null);
            }
        }

        return $this;
    }
}
