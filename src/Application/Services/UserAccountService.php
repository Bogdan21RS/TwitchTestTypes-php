<?php

declare(strict_types=1);

namespace TwitchAnalytics\Application\Services;

use TwitchAnalytics\Domain\Interfaces\UserRepositoryInterface;
use TwitchAnalytics\Domain\Exceptions\UserNotFoundException;

class UserAccountService
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {
    }


    public function getAccountAge(string $displayName): array
    {
        $user = $this->userRepository->findByDisplayName($displayName);

        if ($user === null) {
            throw new UserNotFoundException($displayName);
        }

        $daysSinceCreation = $this->calculateDaysSinceCreation($user->getCreatedAt());

        return [
            'name' => $user->getDisplayName(),
            'days_since_creation' => $daysSinceCreation,
            'created_at' => $user->getCreatedAt()
        ];
    }

    private function calculateDaysSinceCreation(string $createdAt): int
    {
        $createdDate = new \DateTime($createdAt);
        $now = new \DateTime();
        return (int) $createdDate->diff($now)->days;
    }
}
