<?php

declare(strict_types=1);

namespace TwitchAnalytics\Controllers\GetUserPlatformAge;

use TwitchAnalytics\Application\Services\UserAccountService;
use TwitchAnalytics\Domain\Exceptions\UserNotFoundException;
use TwitchAnalytics\Domain\Exceptions\ApplicationException;

class GetUserPlatformAgeController
{
    public function __construct(
        private UserAccountService $userAccountService,
        private UserNameValidator $userNameValidator
    ) {
    }

    public function __invoke(): void
    {
        try {
            $name = $this->userNameValidator->validate($_GET['name'] ?? null);
            $result = $this->userAccountService->getAccountAge($name);
            $this->sendJsonResponse($result);
        } catch (ValidationException $e) {
            $this->sendErrorResponse($e->getMessage(), 400);
        } catch (UserNotFoundException $e) {
            $this->sendErrorResponse($e->getMessage(), 404);
        } catch (ApplicationException $e) {
            $this->sendErrorResponse($e->getMessage(), 400);
        } catch (\Exception $e) {
            $this->sendErrorResponse('An unexpected error occurred', 500);
        }
    }

    private function sendJsonResponse(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    private function sendErrorResponse(string $message, int $statusCode): void
    {
        $response = [
            'error' => $this->getErrorType($statusCode),
            'message' => $message,
            'status' => $statusCode
        ];
        $this->sendJsonResponse($response, $statusCode);
    }

    private function getErrorType(int $statusCode): string
    {
        return match ($statusCode) {
            400 => 'INVALID_REQUEST',
            404 => 'USER_NOT_FOUND',
            default => 'INTERNAL_ERROR',
        };
    }
}
