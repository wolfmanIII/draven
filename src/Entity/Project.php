<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[ORM\Table(
    name: 'project',
    uniqueConstraints: [new ORM\UniqueConstraint(name: 'uniq_project_slug', columns: ['slug'])],
    indexes: [new ORM\Index(name: 'idx_project_is_active', columns: ['is_active'])]
)]
class Project
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid')]
    private ?Uuid $id = null;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\Column(length: 255)]
    private string $slug;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: 'boolean')]
    private bool $isActive = true;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $updatedAt;

    #[ORM\OneToMany(mappedBy: 'project', targetEntity: Environment::class, orphanRemoval: true)]
    private Collection $environments;

    #[ORM\OneToMany(mappedBy: 'project', targetEntity: RepoIntegration::class, orphanRemoval: true)]
    private Collection $repoIntegrations;

    #[ORM\OneToMany(mappedBy: 'project', targetEntity: DeployJob::class, orphanRemoval: true)]
    private Collection $deployJobs;

    public function __construct()
    {
        $this->id = Uuid::v7();
        $now = new \DateTimeImmutable();
        $this->createdAt = $now;
        $this->updatedAt = $now;
        $this->environments = new ArrayCollection();
        $this->repoIntegrations = new ArrayCollection();
        $this->deployJobs = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
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

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

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
     * @return Collection<int, Environment>
     */
    public function getEnvironments(): Collection
    {
        return $this->environments;
    }

    public function addEnvironment(Environment $environment): self
    {
        if (!$this->environments->contains($environment)) {
            $this->environments->add($environment);
            $environment->setProject($this);
        }

        return $this;
    }

    public function removeEnvironment(Environment $environment): self
    {
        if ($this->environments->removeElement($environment)) {
            if ($environment->getProject() === $this) {
                $environment->setProject(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, RepoIntegration>
     */
    public function getRepoIntegrations(): Collection
    {
        return $this->repoIntegrations;
    }

    public function addRepoIntegration(RepoIntegration $integration): self
    {
        if (!$this->repoIntegrations->contains($integration)) {
            $this->repoIntegrations->add($integration);
            $integration->setProject($this);
        }

        return $this;
    }

    public function removeRepoIntegration(RepoIntegration $integration): self
    {
        if ($this->repoIntegrations->removeElement($integration)) {
            if ($integration->getProject() === $this) {
                $integration->setProject(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, DeployJob>
     */
    public function getDeployJobs(): Collection
    {
        return $this->deployJobs;
    }

    public function addDeployJob(DeployJob $job): self
    {
        if (!$this->deployJobs->contains($job)) {
            $this->deployJobs->add($job);
            $job->setProject($this);
        }

        return $this;
    }

    public function removeDeployJob(DeployJob $job): self
    {
        if ($this->deployJobs->removeElement($job)) {
            if ($job->getProject() === $this) {
                $job->setProject(null);
            }
        }

        return $this;
    }
}
