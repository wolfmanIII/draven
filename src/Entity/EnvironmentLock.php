<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'environment_lock')]
class EnvironmentLock
{
    #[ORM\Id]
    #[ORM\OneToOne(inversedBy: 'lock', targetEntity: Environment::class)]
    #[ORM\JoinColumn(name: 'environment_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private ?Environment $environment = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?DeployJob $job = null;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $lockedAt;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $expiresAt = null;

    public function __construct()
    {
        $this->lockedAt = new \DateTimeImmutable();
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

    public function getJob(): ?DeployJob
    {
        return $this->job;
    }

    public function setJob(?DeployJob $job): self
    {
        $this->job = $job;

        return $this;
    }

    public function getLockedAt(): \DateTimeImmutable
    {
        return $this->lockedAt;
    }

    public function setLockedAt(\DateTimeImmutable $lockedAt): self
    {
        $this->lockedAt = $lockedAt;

        return $this;
    }

    public function getExpiresAt(): ?\DateTimeImmutable
    {
        return $this->expiresAt;
    }

    public function setExpiresAt(?\DateTimeImmutable $expiresAt): self
    {
        $this->expiresAt = $expiresAt;

        return $this;
    }
}
