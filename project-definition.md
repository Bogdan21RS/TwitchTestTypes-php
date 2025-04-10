# TwitchAnalytics PHP Project Specification

## Project Overview
A PHP-based REST API that provides Twitch user account analytics, focusing on account age calculation based on display names. The project follows Clean Architecture principles and modern PHP practices.

## Technical Stack
- PHP 8.2+
- Laravel Framework 10.x
- Composer for dependency management
- PHPUnit for testing
- PHP_CodeSniffer for code style enforcement

## Project Structure
```
src/
├── Api/
│   └── Controllers/
│       └── UserController.php
├── Application/
│   └── Services/
│       └── UserAccountService.php
├── Domain/
│   ├── Managers/
│   │   └── UserManager.php
│   ├── Models/
│   │   └── User.php
│   └── Interfaces/
│       └── UserRepositoryInterface.php
├── Infrastructure/
│   ├── Repositories/
│   │   └── MockUserRepository.php
│   └── Data/
│       └── mock-users.json
└── Tests/
    ├── Unit/
    └── Integration/
```

## Mock Data Structure
File: `Infrastructure/Data/mock-users.json`
```json
[
    {
        "id": "12345",
        "login": "ninja",
        "display_name": "Ninja",
        "type": "",
        "broadcaster_type": "partner",
        "description": "Professional Gamer and Streamer",
        "profile_image_url": "https://example.com/ninja.jpg",
        "offline_image_url": "https://example.com/ninja-offline.jpg",
        "view_count": 500000,
        "created_at": "2011-11-20T00:00:00Z"
    }
]
```

## API Endpoint Specification

### Get User Account Age
Retrieves information about a Twitch user's account age based on their display name.

**Endpoint:** `GET /api/users/age`

**Parameters:**
- `name` (required): The name of the Twitch user

**Example Request:**
```bash
curl -X GET "http://localhost:8000/api/users/age?name=Ninja"
```

**Success Response (200 OK):**
```json
{
    "name": "Ninja",
    "days_since_creation": 4482,
    "created_at": "2011-11-20T00:00:00Z"
}
```

**Error Responses:**
- 400 Bad Request
  ```json
  {
      "error": "INVALID_REQUEST",
      "message": "Name parameter is required",
      "status": 400
  }
  ```

- 404 Not Found
  ```json
  {
      "error": "USER_NOT_FOUND",
      "message": "No user found with given name: {name}",
      "status": 404
  }
  ```

- 500 Internal Server Error
  ```json
  {
      "error": "INTERNAL_ERROR",
      "message": "An unexpected error occurred",
      "status": 500
  }
  ```

## Component Responsibilities

### 1. API Layer (Controllers)
- Handle HTTP requests and responses
- Validate input parameters
- Route to appropriate service
- Format API responses
- Handle error responses

### 2. Application Layer (Services)
- Orchestrate business logic
- Calculate account age
- Validate business rules
- Transform data between layers

### 3. Domain Layer
#### Models
- Define User entity and its properties
- Implement domain logic related to user data

#### Managers
- Implement core business logic
- Handle user data retrieval
- Calculate days since account creation

#### Interfaces
- Define contracts for data access
- Ensure loose coupling between layers

### 4. Infrastructure Layer
#### Repositories
- Implement data access logic
- Handle mock data retrieval
- Implement caching if needed

## Implementation Details

### User Flow
1. Client sends GET request with name
2. Controller validates input
3. Service calls manager to process request
4. Manager uses repository to fetch user data
5. Calculate days since account creation
6. Return formatted response

### Error Handling
- All errors must be caught and transformed into appropriate API responses
- Use custom exception classes for different error types
- Log all errors for debugging
- Return consistent error response format

### Validation Rules
- Name must not be empty
- Name must be a string
- Name maximum length: 25 characters
- Name minimum length: 1 character

### Testing Requirements
- Unit tests for all business logic
- Integration tests for API endpoints
- Mock repository tests
- Error handling tests

## Code Quality Standards
- PSR-12 coding standard
- PHPDoc blocks for all public methods
- Type hints for all method parameters and return types
- Immutable objects where possible
- Dependency injection
- SOLID principles adherence

## Development Setup Instructions
1. Clone repository
2. Install dependencies: `composer install`
3. Copy `.env.example` to `.env`
4. Generate app key: `php artisan key:generate`
5. Create mock data file in `Infrastructure/Data`
6. Run tests: `php artisan test`
7. Start development server: `php artisan serve`

## Security Considerations
- Input validation and sanitization
- Rate limiting on API endpoints
- Error messages should not expose internal details
- CORS configuration for API access
- Request validation middleware
