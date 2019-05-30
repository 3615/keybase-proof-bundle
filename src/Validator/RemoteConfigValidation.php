<?php

/*
 * This file is part of the 3615/keybase-proof-bundle project.
 *
 * (c) Romain Gautier <gh@romain.sh>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace 💻3615\Keybase\ProofBundle\Validator;

use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use 💻3615\Keybase\ProofBundle\Exception\MissingOrInvalidInputsException;
use 💻3615\Keybase\ProofBundle\Exception\UnhandledException;

class RemoteConfigValidation
{
    private $httpClient;
    private $serializer;
    private $keybaseProofConfig;

    public function __construct(HttpClientInterface $httpClient, SerializerInterface $serializer, array $keybaseProofConfig)
    {
        $this->httpClient = $httpClient;
        $this->serializer = $serializer;
        $this->keybaseProofConfig = $keybaseProofConfig;
    }

    /**
     * Throw an exception if the configuration is wrong (or if there is a problem with Keybase).
     *
     * @throws MissingOrInvalidInputsException
     * @throws UnhandledException
     */
    public function __invoke(): void
    {
        $response = $this->httpClient->request('POST', 'https://keybase.io/_/api/1.0/validate_proof_config.json', [
            'body' => ['config' => $this->serializer->serialize($this->keybaseProofConfig, 'json')],
        ]);

        if ($response->getStatusCode() > 200) {
            throw new UnhandledException();
        }

        $keybaseResponse = $response->toArray();

        switch ($code = $keybaseResponse['status']['code']) {
            case 0:
                return;
            case 100:
                $jsonPosition = \strpos($keybaseResponse['status']['desc'], '{');
                $message = \substr($keybaseResponse['status']['desc'], 0, $jsonPosition);
                $json = \substr($keybaseResponse['status']['desc'], $jsonPosition);

                $fieldsWithError = \json_decode($json, false, 512, JSON_THROW_ON_ERROR);
                $details = '';
                foreach ($fieldsWithError as $field => $error) {
                    $details .= "- ${field}: ${error}\n";
                }

                throw new MissingOrInvalidInputsException(\sprintf("%s\n%s", \ucfirst($message), $details));
            default:
                throw new UnhandledException(\sprintf('Keybase status code "%d" not handled.', $code));
        }
    }
}
