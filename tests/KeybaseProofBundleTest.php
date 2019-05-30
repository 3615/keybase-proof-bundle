<?php

/*
 * This file is part of the 3615/keybase-proof-bundle project.
 *
 * (c) Romain Gautier <gh@romain.sh>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace 💻3615\Keybase\ProofBundle\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use 💻3615\Keybase\ProofBundle\KeybaseProofBundle;

class KeybaseProofBundleTest extends TestCase
{
    public function testItExtendsTheAbstractClassBundle()
    {
        $this->assertInstanceOf(Bundle::class, new KeybaseProofBundle());
    }
}
