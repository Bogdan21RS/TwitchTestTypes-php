<?php

namespace TwitchAnalytics\Tests\Unit\Controllers\GetUserPlatformAge;

use Random\RandomException;
use TwitchAnalytics\Controllers\GetUserPlatformAge\UserNameValidator;
use PHPUnit\Framework\TestCase;
use TwitchAnalytics\Controllers\GetUserPlatformAge\ValidationException;

class UserNameValidatorTest extends TestCase
{
    private const MAX_LENGTH = 25;
    private const MIN_LENGTH = 3;

    private UserNameValidator $userNameValidator;
    protected function setUp(): void
    {
        parent::setUp();

        $this->userNameValidator = new UserNameValidator();
    }

    /**
     * @test
     **/
    public function emptyNameReturnsException(): void
    {
        $this->expectException(ValidationException::class);

        $this->userNameValidator->validate('');
    }

    /**
     * @test
     *
     * @throws RandomException
     */
    public function nameLongerThanMaxLengthReturnsException(): void
    {
        $this->expectException(ValidationException::class);

        $name = substr(bin2hex(random_bytes(self::MAX_LENGTH + 1)), 0, self::MAX_LENGTH + 1);

        $this->userNameValidator->validate($name);
    }

    /**
     * @test
     *
     * @throws RandomException
     */
    public function nameLowerThanMinLengthReturnsException(): void
    {
        $this->expectException(ValidationException::class);

        $name = substr(bin2hex(random_bytes(self::MIN_LENGTH - 1)), 0, self::MIN_LENGTH - 1);

        $this->userNameValidator->validate($name);
    }

    /**
     * @test
     *
     * @throws RandomException
     */
    public function nameWithQuotesWithinPermittedLengthReturnsNameWithoutQuotes(): void
    {
        $normalName = substr(bin2hex(random_bytes(self::MIN_LENGTH + 1)), 0, self::MIN_LENGTH + 1);
        $nameWithQuotes = $normalName .  "''";

        $this->assertEquals($normalName . "&apos;&apos;", $this->userNameValidator->validate($nameWithQuotes));
    }

    /**
     * @test
     *
     * @throws RandomException
     */
    public function nameWithHtmlTagsWithinPermittedLengthReturnsNameWithoutQuotes(): void
    {
        $normalName = substr(bin2hex(random_bytes(self::MIN_LENGTH + 1)), 0, self::MIN_LENGTH + 1);
        $nameWithHtmlLabel = $normalName .  "<script>";

        $this->assertEquals($normalName, $this->userNameValidator->validate($nameWithHtmlLabel));
    }
}
