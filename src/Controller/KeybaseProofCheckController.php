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
use Symfony\Component\HttpFoundation\Response;

class KeybaseProofCheckController extends AbstractController
{
    public function __invoke(): Response
    {
        return $this->json(
        );
    }
}
