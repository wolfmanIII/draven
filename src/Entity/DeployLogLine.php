<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(
    name: 'deploy_log_line',
    indexes: [new ORM\Index(name: 'idx_deploy_log_line_job_seq', columns: ['job_id', 'seq'])]
)]
class DeployLogLine
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: 'bigint')]
    private ?string $id = null;

    #[ORM\ManyToOne(inversedBy: 'logLines')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?DeployJob $job = null;

    #[ORM\Column(type: 'integer')]
    private int $seq;

    #[ORM\Column(length: 32)]
    private string $stream;

    #[ORM\Column(type: 'text')]
    private string $message;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?string
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

    public function getSeq(): int
    {
        return $this->seq;
    }

    public function setSeq(int $seq): self
    {
        $this->seq = $seq;

        return $this;
    }

    public function getStream(): string
    {
        return $this->stream;
    }

    public function setStream(string $stream): self
    {
        $this->stream = $stream;

        return $this;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

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
}
