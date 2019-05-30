<?php

/*
 * This file is part of the 3615/keybase-proof-bundle project.
 *
 * (c) Romain Gautier <gh@romain.sh>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace 💻3615\Keybase\ProofBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

trait KeybaseProofTrait
{
    /**
     * @var int
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=128)
     */
    private $keybaseUsername;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=66)
     */
    private $signatureHash;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default" : false})
     */
    private $isVerifed;

    /**
     * @var \DateTimeImmutable
     *
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    public function __construct(UserInterface $user, string $keybaseUsername, string $signatureHash)
    {
        $this->user = $user;
        $this->keybaseUsername = $keybaseUsername;
        $this->signatureHash = $signatureHash;
        $this->isVerifed = false;
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $date = !$createdAt instanceof \DateTimeImmutable
            ? \DateTimeImmutable::createFromMutable($createdAt)
            : $createdAt
        ;

        if ($date->diff($this->createdAt)) {
            $this->createdAt = $date;
        }

        return $this;
    }

    public function getKeybaseUsername(): ?string
    {
        return $this->keybaseUsername;
    }

    public function setKeybaseUsername(string $keybaseUsername): self
    {
        $this->keybaseUsername = $keybaseUsername;

        return $this;
    }

    public function getSignatureHash(): ?string
    {
        return $this->signatureHash;
    }

    public function setSignatureHash(string $signatureHash): self
    {
        $this->signatureHash = $signatureHash;

        return $this;
    }
}
