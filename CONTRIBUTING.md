# Contributing to slack-messages

Thank you for considering contributing to slack-messages! We appreciate your help and value your time. This document provides guidelines to help make the contribution process smooth and efficient.

## Code of Conduct

By participating in this project, you are expected to uphold our [Code of Conduct](./CODE_OF_CONDUCT.md).

## Getting Started

- Fork the repository and clone it to your local machine.
- Install the dependencies: `composer install`
- Run the tests to make sure everything is working as expected: `vendor/bin/phpunit`

## Reporting Bugs

Before reporting a bug, please search the existing issues to see if it has already been reported. If not, create a new issue using the provided bug report template.

## Feature Requests

If you have a suggestion for a new feature or improvement, please create a new issue using the feature request template.

## Submitting Changes

1. Create a new branch for your changes: `git checkout -b my-feature-branch`
2. Make your changes and commit them with a descriptive commit message.
3. Make sure to follow the coding style guidelines and add tests for your changes.
4. Run the tests to ensure your changes don't introduce new issues: `vendor/bin/phpunit`
5. Push your branch to your fork: `git push origin my-feature-branch`
6. Open a pull request against the main repository using the appropriate pull request template.

## Coding Style Guidelines

slack-messages follows the [PSR-12: Extended Coding Style](https://www.php-fig.org/psr/psr-12/) standard. Please ensure your code adheres to this standard. You can use tools like [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer) to check your code for compliance.

## Testing

Please add tests for any new features or bug fixes. Tests should be written using [PHPUnit](https://phpunit.de/). Make sure all tests pass before submitting your pull request.


## Questions and Feedback

If you have any questions or feedback, please don't hesitate to reach out by opening a new issue or by contacting the maintainers directly.

Thank you for your contribution!
