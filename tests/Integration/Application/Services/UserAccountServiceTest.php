<?php

namespace TwitchAnalytics\Tests\Integration\Application\Services;

use Mockery;
use PHPUnit\Framework\TestCase;
use TwitchAnalytics\Application\Services\UserAccountService;
use TwitchAnalytics\Domain\Exceptions\UserNotFoundException;
use TwitchAnalytics\Domain\Interfaces\UserRepositoryInterface;
use TwitchAnalytics\Domain\Models\User;

class UserAccountServiceTest extends TestCase
{
    /**
     * @test
     **/
    public function accountAgeOfNonExistingAccountGivesError(): void
    {
        $userName = '';
        $userRepositoryInterface = Mockery::mock(UserRepositoryInterface::class);
        $userRepositoryInterface
            ->expects('findByDisplayName')
            ->with($userName)
            ->andReturn(null);
        $userAccountService = new UserAccountService($userRepositoryInterface);

        $this->expectException(UserNotFoundException::class);

        $userAccountService->getAccountAge($userName);
    }

    /**
     * @test
     *
     * @throws \DateMalformedStringException
     */
    public function accountAgeOfOneYearOldUserIsOneYear(): void
    {
        $userName = '';
        $user = Mockery::mock(User::class);

        $oneYearAgoToday = (new \DateTime() )->modify('-1 year');
        $user->allows('getCreatedAt')->andReturn($oneYearAgoToday->format('Y-m-d'));
        $user->allows('getDisplayName')->andReturn(':)');

        $userRepositoryInterface = Mockery::mock(UserRepositoryInterface::class);
        $userRepositoryInterface
            ->expects('findByDisplayName')
            ->with($userName)
            ->andReturn($user);

        $userAccountService = new UserAccountService($userRepositoryInterface);

        $response = $userAccountService->getAccountAge($userName);

        $this->assertEquals(365, $response['days_since_creation']);
    }
}
