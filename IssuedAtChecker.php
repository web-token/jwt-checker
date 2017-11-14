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

namespace Jose\Component\Checker;

/**
 * Class IssuedAtChecker.
 */
final class IssuedAtChecker implements ClaimChecker
{
    private const CLAIM_NAME = 'iat';

    /**
     * {@inheritdoc}
     */
    public function checkClaim($value)
    {
        if (!is_int($value)) {
            throw new \InvalidArgumentException('The claim "iat" must be an integer.');
        }
        if (time() < $value) {
            throw new \InvalidArgumentException('The JWT is issued in the future.');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function supportedClaim(): string
    {
        return self::CLAIM_NAME;
    }
}
