<?php

/*
 * This file is part of the 3615/keybase-proof-bundle project.
 *
 * (c) Romain Gautier <gh@romain.sh>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace 💻3615\Keybase\ProofBundle\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use 💻3615\Keybase\ProofBundle\Entity\KeybaseProofInterface;

class KeybaseProofExtension extends AbstractExtension
{
    private $keybaseProofConfig;

    public function __construct(array $keybaseProofConfig)
    {
        $this->keybaseProofConfig = $keybaseProofConfig;
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('keybase_proof_url', [$this, 'url']),
            new TwigFilter('keybase_proof_badge_url', [$this, 'badgeUrl']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('keybase_proof_status', [$this, 'url']),
            new TwigFunction('keybase_proof_badge_url', [$this, 'badgeUrl']),
        ];
    }

    public function url(KeybaseProofInterface $keybaseProof): string
    {
        return \sprintf(
            'https://keybase.io/%s/sigs/%s',
            $keybaseProof->getKeybaseUsername(),
            $keybaseProof->getSignatureHash()
        );
    }

    public function badgeUrl(KeybaseProofInterface $keybaseProof): string
    {
        return \sprintf(
            'https://keybase.io/%s/proof_badge/%s?%s',
            $keybaseProof->getKeybaseUsername(),
            $keybaseProof->getSignatureHash(),
            \http_build_query([
                'domain' => $this->keybaseProofConfig['domain'],
                'username' => $keybaseProof->getUsername(),
            ])
        );
    }
}
