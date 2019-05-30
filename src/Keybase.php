<?php

/*
 * This file is part of the 3615/keybase-proof-bundle project.
 *
 * (c) Romain Gautier <gh@romain.sh>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace 💻3615\Keybase\ProofBundle;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class Keybase
{
    private $httpClient;
    private $keybaseProofConfig;

    public function __construct(
        HttpClientInterface $httpClient,
        array $keybaseProofConfig
    ) {
        $this->httpClient = $httpClient;
        $this->keybaseProofConfig = $keybaseProofConfig;
    }

    /**
     * Check the proof validity in Keybase via the `sig/proof_valid` endpoint.
     * Returns a boolean `proof_valid` indicating if the signature is valid for
     * the given domain/kb_username/username/sig_hash combination.
     * Before storing a signature `proof_valid=True` must hold.
     */
    public function isProofValid(
        string $username,
        string $signatureHash,
        string $keybaseUsername
    ): bool {
        $params = \http_build_query([
            'domain' => $this->keybaseProofConfig['domain'],
            'username' => $username,
            'kb_username' => $keybaseUsername,
            'sig_hash' => $signatureHash,
        ]);
        $response = $this->httpClient->request(
            'GET',
            'https://keybase.io/_/api/1.0/sig/proof_valid.json?'.$params
        );

        if (200 !== $response->getStatusCode()) {
            return false;
        }

        return $response->toArray()['proof_valid'] ?? false;
    }

    /**
     * Checks the proof status in Keybase via the `sig/proof_live` endpoint.
     * Returns a tuple (`proof_valid`, `proof_live`) indicating if the signature
     * is live and if the proof is publicly verifiable to the Keybase
     * client/server, respectively.
     * Keybase suggests an asynchronous task that calls this endpoint during the
     * proof creation flow. It will return (proof_valid=True, proof_live=False)
     * until Keybase has seen it being served from this service's API, at which
     * point it will update to (proof_valid=True, proof_live=True). This is the
     * happy path.  It may also make sense to call this endpoint periodically or
     * whenever a user inspects their own proof/profile/settings to update local
     * records.  If a user revokes their proof from Keybase, this will return
     * (proof_valid=False, proof_live=False).
     *
     * @return bool[]
     */
    public function isProofLive(
        string $username,
        string $signatureHash,
        string $keybaseUsername
    ): array {
        $params = \http_build_query([
            'domain' => $this->keybaseProofConfig['domain'],
            'username' => $username,
            'kb_username' => $keybaseUsername,
            'sig_hash' => $signatureHash,
        ]);
        $response = $this->httpClient->request(
            'GET',
            'https://keybase.io/_/api/1.0/sig/proof_live.json?'.$params
        );

        if (200 !== $response->getStatusCode()) {
            return [false, false];
        }

        $responseBody = $response->toArray();

        return [
            $responseBody['proof_valid'] ?? false,
            $responseBody['proof_live'] ?? false,
        ];
    }
}
