<p><a href="https://keybase.io"><img align="right" width="120" height="140" src="./src/Resources/public/image/keybase_logo.svg" clear="right"></a></p>
<p><a href="https://symfony.com"><img align="right" width="120" height="120" src="./src/Resources/image/symfony_logo.svg" clear="right"></a></p>

# 🗝 KeybaseProofBundle

[![Packagist Version](https://img.shields.io/packagist/v/3615/keybase-proof-bundle.svg)](https://packagist.org/packages/3615/keybase-proof-bundle)
[![Packagist Downloads](https://img.shields.io/packagist/dm/3615/keybase-proof-bundle.svg)](https://packagist.org/packages/3615/keybase-proof-bundle/stats)
[![PHP from Packagist](https://img.shields.io/packagist/php-v/3615/keybase-proof-bundle.svg)](#)
[![Tests](https://img.shields.io/travis/3615/keybase-proof-bundle.svg)](https://travis-ci.org/3615/keybase-proof-bundle)

- [1️⃣ Setup](#1️⃣-setup)
- [2️⃣ Usage](#2️⃣-usage)

---

## 1️⃣ Setup

```bash
composer require 3615/keybase-proof-bundle
```

### Configuration

```bash
bin/console config:dump-reference keybase_proof
# For more details: https://keybase.io/docs/proof_integration_guide#1-config
```

```yaml
# config/keybase-proof.yaml
keybase_proof:
  version: 1
  domain: beeactivists.com
  display_name: Bee Activists
  username:
    re: '^[a-zA-Z0-9_]{2,20}$'
    min: 2
    max: 20
  brand_color: '#FFB800'
  logo:
    svg_black: https://beeactivists.com/small-black-logo.svg
    svg_full: https://beeactivists.com/full-color.logo.svg
  description: Next gen social network using big data & AI in the cloud 🤖☁️.
  prefill_url: https://beeactivists.com/new-profile-proof?kb_username=%%{kb_username}&username=%%{username}&token=%%{sig_hash}&kb_ua=%%{kb_ua}
  profile_url: https://beeactivists.com/profile/%%{username}
  check_url: https://api.beeactivists.com/keybase-proofs.json?username=%%{username}
  check_path: ["signatures"]
  avatar_path: ["avatar"]
  contact: ["admin@beeactivists.com", "sassybedazzle@keybase"]

  # used for debug when doing `bin/console keybase:proof:publish-config --dry-run`
  my_username: t_alice
```

### Entity

```php
<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use 💻3615\Keybase\ProofBundle\Entity\KeybaseProofInterface;
use 💻3615\Keybase\ProofBundle\Entity\KeybaseProofTrait;

/**
 * @ORM\Entity()
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(name="keybase_proof_idx", columns={
 *      "keybase_username",
 *      "user_id",
 * })})
 */
class KeybaseProof implements KeybaseProofInterface
{
    use KeybaseProofTrait;

    /**
     * @var int|null
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var User|null
     *
     * @ORM\OneToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getUsername(): ?string
    {
        if (!$this->user) {
            return null;
        }

        return $this->user->getUsername();
    }
}
```

### Routes

```
# config/routes.yaml

keybase_proof:
    resource: '@KeybaseProofBundle/Resources/config/routes.php'
    type: php
```

---

## 2️⃣ Usage

1. Setup everything
2. Verify that the configuration is valid

```bash
bin/console keybase:proof:validate-config
```

3. Send your configuration for manual validation to be added on keybase

```bash
bin/console keybase:proof:publish-config --dry-run # send the message to yourself before
bin/console keybase:proof:publish-config
```

4. Once in production, if you update your config, increase the version number then republish your config (step 3)


**Twig extension** :

```twig
<a href="{{ keybase_proof_url(keybase_proof) }}">
    <img alt="Keybase proof status" src="{{ keybase_proof_badge_url(keybase_proof) }}">
</a>
```


---
---
---

Don't hesitate to give us a star: [![stars](https://img.shields.io/github/stars/3615/keybase-proof-bundle.svg?style=social)](https://github.com/3615/keybase-proof-bundle/stargazers)

[![BTC: 1GdgM1edTcSCLvt46Wnx8GfxcKKeECfBGk](https://img.shields.io/keybase/btc/mykiwi.svg)](https://keybase.io/mykiwi)  
[![ZEC: t1bNdSoehjwFSG43fsYfAhzRtgAeNbyCF1e](https://img.shields.io/keybase/zec/mykiwi.svg)](https://keybase.io/mykiwi)  
[![XLM: GD7BI57EKKNLIPYSRCZAGSVG4QZX7EYMXIYMOT6ASLCLA5JE255N7TKL](https://img.shields.io/keybase/xlm/mykiwi.svg)](https://keybase.io/mykiwi)
