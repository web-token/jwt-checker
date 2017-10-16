<?php

declare(strict_types=1);

/*
 * The MIT License (MIT)
 *
 * Copyright (c) 2014-2017 Spomky-Labs
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE file for details.
 */

namespace Jose\Component\Checker\Tests;

use Jose\Component\Checker\AudienceChecker;
use Jose\Component\Checker\ClaimCheckerManager;
use Jose\Component\Checker\ClaimCheckerManagerFactory;
use Jose\Component\Checker\ExpirationTimeChecker;
use Jose\Component\Checker\IssuedAtChecker;
use Jose\Component\Checker\NotBeforeChecker;
use Jose\Component\Core\Converter\JsonConverter;
use PHPUnit\Framework\TestCase;

/**
 * @group ClaimCheckerManager
 * @group Functional
 */
final class ClaimCheckerManagerFactoryTest extends TestCase
{
    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The claim checker with the alias "foo" is not supported.
     */
    public function testAliasDoesNotExist()
    {
        $this->getClaimCheckerManagerFactory()->create(['foo']);
    }

    public function testICanCreateACheckerManager()
    {
        $manager = $this->getClaimCheckerManagerFactory()->create(['exp', 'iat', 'nbf', 'aud']);
        self::assertInstanceOf(ClaimCheckerManager::class, $manager);
    }

    public function testSuccess()
    {
        $payload = [
            'exp' => time() + 3600,
            'iat' => time() - 1000,
            'nbf' => time() - 100,
        ];
        $manager = $this->getClaimCheckerManagerFactory()->create(['exp', 'iat', 'nbf', 'aud']);
        $result = $manager->check($payload);
        self::assertEquals($payload, $result);
    }

    /**
     * @var ClaimCheckerManagerFactory|null
     */
    private $claimCheckerManagerFactory = null;

    /**
     * @return ClaimCheckerManagerFactory
     */
    private function getClaimCheckerManagerFactory(): ClaimCheckerManagerFactory
    {
        if (null === $this->claimCheckerManagerFactory) {
            $this->claimCheckerManagerFactory = new ClaimCheckerManagerFactory(new JsonConverter());
            $this->claimCheckerManagerFactory->add('exp', new ExpirationTimeChecker());
            $this->claimCheckerManagerFactory->add('iat', new IssuedAtChecker());
            $this->claimCheckerManagerFactory->add('nbf', new NotBeforeChecker());
            $this->claimCheckerManagerFactory->add('aud', new AudienceChecker('My Service'));
        }

        return $this->claimCheckerManagerFactory;
    }
}
