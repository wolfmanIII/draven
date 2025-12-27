<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[ORM\Table(name: '`user`', uniqueConstraints: [new ORM\UniqueConstraint(name: 'uniq_user_email', columns: ['email'])])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid')]
    private ?Uuid $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private string $email;

    #[ORM\Column(type: 'json')]
    private array $roles = [];

    #[ORM\Column]
    private string $password;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $updatedAt;

    #[ORM\OneToMany(mappedBy: 'requestedBy', targetEntity: DeployJob::class)]
    private Collection $deployJobsRequested;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: ApprovalDecision::class)]
    private Collection $approvalDecisions;

    public function __construct()
    {
        $this->id = Uuid::v7();
        $now = new \DateTimeImmutable();
        $this->createdAt = $now;
        $this->updatedAt = $now;
        $this->deployJobsRequested = new ArrayCollection();
        $this->approvalDecisions = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_values(array_unique($roles));
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function eraseCredentials(): void
    {
        // No transient sensitive data stored on the user entity.
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
    public function getDeployJobsRequested(): Collection
    {
        return $this->deployJobsRequested;
    }

    public function addDeployJobsRequested(DeployJob $deployJob): self
    {
        if (!$this->deployJobsRequested->contains($deployJob)) {
            $this->deployJobsRequested->add($deployJob);
            $deployJob->setRequestedBy($this);
        }

        return $this;
    }

    public function removeDeployJobsRequested(DeployJob $deployJob): self
    {
        if ($this->deployJobsRequested->removeElement($deployJob)) {
            if ($deployJob->getRequestedBy() === $this) {
                $deployJob->setRequestedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ApprovalDecision>
     */
    public function getApprovalDecisions(): Collection
    {
        return $this->approvalDecisions;
    }

    public function addApprovalDecision(ApprovalDecision $decision): self
    {
        if (!$this->approvalDecisions->contains($decision)) {
            $this->approvalDecisions->add($decision);
            $decision->setUser($this);
        }

        return $this;
    }

    public function removeApprovalDecision(ApprovalDecision $decision): self
    {
        if ($this->approvalDecisions->removeElement($decision)) {
            if ($decision->getUser() === $this) {
                $decision->setUser(null);
            }
        }

        return $this;
    }
}
