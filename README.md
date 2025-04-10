# Twitch Analytics PHP Project

A PHP-based REST API that provides Twitch user account analytics, focusing on account age calculation based on display names.

## Requirements

- PHP 8.2 or higher
- Composer
- PHP extensions: json

## Installation

1. Clone the repository
2. Install dependencies:
```bash
composer install
```

## Running the Application

Start the PHP development server:
```bash
composer start
```
Or manually:
```bash
php -S localhost:8000 -t public
```

## API Endpoints

### Get User Platform Age

**Endpoint:** `GET /api/users/platform-age`

**Parameters:**
- `name` (required): The name of the Twitch user (3-25 characters)

**Example Request:**
```bash
curl -X GET "http://localhost:8000/api/users/platform-age?name=Ninja"
```

**Success Response (200 OK):**
```json
{
    "name": "Ninja",
    "days_since_creation": 4482,
    "created_at": "2011-11-20T00:00:00Z"
}
```

**Validation Rules:**
- Name parameter is required
- Name must be between 3 and 25 characters long
- Name is case-insensitive

## Project Structure

```
src/
├── Application/
│   └── Services/
│       └── UserAccountService.php
├── Controllers/
│   └── GetUserPlatformAge/
│       ├── GetUserPlatformAgeController.php
│       ├── UserNameValidator.php
│       └── ValidationException.php
├── Domain/
│   ├── Exceptions/
│   │   ├── ApplicationException.php
│   │   └── UserNotFoundException.php
│   ├── Interfaces/
│   │   └── UserRepositoryInterface.php
│   └── Models/
│       └── User.php
└── Infrastructure/
    ├── ApiClient/
    │   ├── FakeTwitchApiClient.php
    │   └── TwitchApiClientInterface.php
    └── Repositories/
        └── ApiUserRepository.php
```

## Error Handling

The API returns appropriate HTTP status codes and error messages:

- 400 Bad Request: Invalid input parameters
- 404 Not Found: User not found
- 500 Internal Server Error: Unexpected errors

**Error Response Format:**
```json
{
    "error": "ERROR_TYPE",
    "message": "Error description",
    "status": 400
}
```

## Development

The project follows PSR-12 coding standards and uses PHP_CodeSniffer for code style enforcement.

### Available Commands

```bash
# Run tests
composer test

# Run tests with coverage report
composer test:coverage

# Check code style
composer cs-check

# Fix code style
composer cs-fix

# Start development server
composer start
```

### Testing

The project uses PHPUnit for testing and Mockery for mocking dependencies. Tests are organized into two categories:

- **Unit Tests**: Located in `tests/Unit/`
- **Integration Tests**: Located in `tests/Integration/`

To run tests with coverage report:
```bash
composer test:coverage
```
This will generate an HTML coverage report in the `coverage` directory.

### Architecture

The project follows a clean architecture approach with the following components:

- **Controllers**: Handle HTTP requests/responses and input validation
- **Services**: Implement business logic and orchestrate operations
- **Domain**: Contains core business models and interfaces
- **Infrastructure**: Implements data access and external service integration

### API Client

The project includes two implementations for the Twitch API client:

1. `FakeTwitchApiClient`: Provides mock data for development and testing
2. `TwitchApiClientInterface`: Interface for implementing real Twitch API integration

To implement real Twitch API integration, create a new class implementing `TwitchApiClientInterface` and update the dependency injection in `index.php`.
