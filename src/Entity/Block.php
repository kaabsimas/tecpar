<?php

namespace App\Entity;

use App\Repository\BlockRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BlockRepository::class)
 */
class Block
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $batch;

    /**
     * @ORM\Column(type="integer")
     */
    private $block_height;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $entry;

    /**
     * @ORM\Column(type="string", length=8)
     */
    private $nonce;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $hash;

    /**
     * @ORM\Column(type="integer")
     */
    private $tries;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBatch(): ?\DateTimeInterface
    {
        return $this->batch;
    }

    public function setBatch(\DateTimeInterface $batch): self
    {
        $this->batch = $batch;

        return $this;
    }

    public function getBlockHeight(): ?int
    {
        return $this->block_height;
    }

    public function setBlockHeight(int $block_height): self
    {
        $this->block_height = $block_height;

        return $this;
    }

    public function getEntry(): ?string
    {
        return $this->entry;
    }

    public function setEntry(string $entry): self
    {
        $this->entry = $entry;

        return $this;
    }

    public function getNonce(): ?string
    {
        return $this->nonce;
    }

    public function setNonce(string $nonce): self
    {
        $this->nonce = $nonce;

        return $this;
    }

    public function getHash(): ?string
    {
        return $this->hash;
    }

    public function setHash(string $hash): self
    {
        $this->hash = $hash;

        return $this;
    }

    public function getTries(): ?int
    {
        return $this->tries;
    }

    public function setTries(int $tries): self
    {
        $this->tries = $tries;

        return $this;
    }
}
