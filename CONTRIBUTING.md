# Contributing to PHPCalendar

Thank you for your interest in contributing to PHPCalendar! This document provides guidelines for development setup, testing, and code standards.

## Development Setup

### Prerequisites

- PHP 8.1 or higher
- Composer
- Git

### Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/codryn/phpcalendar.git
   cd phpcalendar
   ```

2. Install dependencies:
   ```bash
   composer install
   ```

3. Verify installation:
   ```bash
   composer test
   ```

## Code Standards

### PSR-12 Compliance

All code must follow [PSR-12](https://www.php-fig.org/psr/psr-12/) coding standards. We use PHP-CS-Fixer to enforce this:

```bash
composer cs-fix
```

Before committing, always run the fixer to ensure compliance.

### Strict Types

All PHP files must declare strict types:

```php
<?php

declare(strict_types=1);
```

### Type Hints

- Use PHP 8.1 type hints for all parameters and return values
- Document complex types with PHPDoc annotations
- Use union types when appropriate (e.g., `string|null`)

### PHPDoc Blocks

All public methods must have comprehensive PHPDoc blocks:

```php
/**
 * Brief description of what the method does
 *
 * @param int $year Year to check
 * @param bool $strict Enable strict validation
 * @return bool True if valid
 * @throws InvalidArgumentException if year is invalid
 */
public function isValid(int $year, bool $strict = false): bool
{
    // implementation
}
```

## Testing

### Test-Driven Development

PHPCalendar follows TDD principles:

1. Write tests first (Red)
2. Implement code to pass tests (Green)
3. Refactor while keeping tests green (Refactor)

### Running Tests

Run the full test suite:

```bash
composer test
```

Run specific test suites:

```bash
composer test:unit       # Unit tests only
composer test:integration # Integration tests only
composer test:acceptance  # Acceptance tests only
```

Run tests with coverage:

```bash
composer test:coverage
```

### Test Organization

- **Unit Tests** (`tests/Unit/`): Test individual classes in isolation
- **Integration Tests** (`tests/Integration/`): Test multiple components together
- **Acceptance Tests** (`tests/Acceptance/`): Test user stories end-to-end

### Writing Tests

- Use descriptive test method names: `testCreateGregorianCalendarWithCorrectProperties()`
- Follow Arrange-Act-Assert pattern
- One assertion concept per test
- Use data providers for testing multiple scenarios

## Static Analysis

We use PHPStan 2.1 at level 10 (strict) with strict rules:

```bash
composer analyse
```

All code must pass PHPStan analysis without errors before merging.

## Quality Checks

Run all quality checks at once:

```bash
composer ci
```

This runs:
1. PHP-CS-Fixer (code style)
2. PHPStan (static analysis)
3. PHPUnit (full test suite with coverage)

## Pull Request Process

1. **Fork** the repository
2. **Create a feature branch** from `main`:
   ```bash
   git checkout -b feature/my-new-feature
   ```

3. **Write tests** for your changes (TDD)

4. **Implement** your changes

5. **Ensure quality checks pass**:
   ```bash
   composer ci
   ```

6. **Commit** with clear, descriptive messages:
   ```bash
   git commit -m "Add support for custom epoch notations"
   ```

7. **Push** to your fork:
   ```bash
   git push origin feature/my-new-feature
   ```

8. **Open a Pull Request** with:
   - Clear description of changes
   - Reference to any related issues
   - Test coverage report
   - Breaking changes noted

## Code Review Guidelines

### For Contributors

- Respond to feedback promptly
- Keep PRs focused and reasonably sized
- Update tests when changing functionality
- Maintain backward compatibility when possible

### For Reviewers

- Be respectful and constructive
- Focus on code quality and maintainability
- Check test coverage
- Verify documentation is updated

## Adding New Calendar Profiles

When adding a new calendar profile:

1. Create profile class extending `AbstractCalendarProfile`
2. Implement all required interface methods
3. Add metadata with source information
4. Register in `ProfileRegistry::initialize()`
5. Write comprehensive tests (unit + acceptance)
6. Update README with examples
7. Add to documentation

Example structure:

```php
final class MyCalendarProfile extends AbstractCalendarProfile
{
    public function getName(): string { }
    public function getDisplayName(): string { }
    public function getMonthNames(): array { }
    public function getDaysInMonth(int $month, int $year): int { }
    public function isLeapYear(int $year): bool { }
    public function getEpochNotation(): array { }
    public function getFormatPatterns(): array { }
    public function getMetadata(): array { }
}
```

## Documentation

When adding or modifying features:

- Update README.md with usage examples
- Add or update API documentation in `docs/`
- Update CHANGELOG.md following [Keep a Changelog](https://keepachangelog.com/)
- Include inline code comments for complex logic

## License

By contributing to PHPCalendar, you agree that your contributions will be licensed under the MIT License.

## Questions?

- Open an issue for bugs or feature requests
- Start a discussion for questions or ideas
- Contact maintainers for security issues

Thank you for contributing to PHPCalendar!
