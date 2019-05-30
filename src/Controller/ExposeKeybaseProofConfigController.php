<?php

/*
 * This file is part of the 3615/keybase-proof-bundle project.
 *
 * (c) Romain Gautier <gh@romain.sh>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace 💻3615\Keybase\ProofBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ExposeKeybaseProofConfigController extends AbstractController
{
    public function __invoke(Request $request, array $keybaseProofConfig): Response
    {
        return $this->json($keybaseProofConfig, 200, [], $request->query->has('pretty')
            ? ['json_encode_options' => JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE]
            : []
        );
    }
}
