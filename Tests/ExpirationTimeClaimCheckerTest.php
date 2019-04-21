<?php

declare(strict_types=1);

/*
 * The MIT License (MIT)
 *
 * Copyright (c) 2014-2019 Spomky-Labs
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE file for details.
 */

namespace Jose\Component\Checker\Tests;

use Jose\Component\Checker\ExpirationTimeChecker;
use PHPUnit\Framework\TestCase;

/**
 * @group ClaimChecker
 * @group functional
 *
 * @internal
 * @coversNothing
 */
class ExpirationTimeClaimCheckerTest extends TestCase
{
    /**
     * @test
     * @expectedException \Jose\Component\Checker\InvalidClaimException
     * @expectedExceptionMessage "exp" must be an integer.
     */
    public function theExpirationTimeClaimMustBeAnInteger()
    {
        $checker = new ExpirationTimeChecker();
        $checker->checkClaim('foo');
    }

    /**
     * @test
     * @expectedException \Jose\Component\Checker\InvalidClaimException
     * @expectedExceptionMessage The token expired.
     */
    public function theExpirationTimeIsInThePast()
    {
        $checker = new ExpirationTimeChecker();
        $checker->checkClaim(time() - 1);
    }

    /**
     * @test
     */
    public function theExpirationTimeIsInTheFutur()
    {
        $checker = new ExpirationTimeChecker();
        $checker->checkClaim(time() + 3600);
        static::assertEquals('exp', $checker->supportedClaim());
    }
}
