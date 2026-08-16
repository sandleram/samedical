<?php

namespace Tests\Unit\Domain\Integration;

use App\Domain\Integration\TokenMatcher;
use PHPUnit\Framework\TestCase;

class TokenMatcherTest extends TestCase
{
    public function test_empty_expected_never_matches(): void
    {
        $this->assertFalse(TokenMatcher::matches('', 'anything'));
    }

    public function test_matching_token(): void
    {
        $this->assertTrue(TokenMatcher::matches('secret', 'secret'));
    }

    public function test_mismatch(): void
    {
        $this->assertFalse(TokenMatcher::matches('secret', 'other'));
    }
}
