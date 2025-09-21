### Contributing to Laravel Modules (lightweight)

First off, thanks for considering contributing! 🎉
This project is meant to stay simple, lightweight, and production-ready, so contributions should follow the same philosophy.

#### Ways to Contribute
- Report bugs via GitHub Issues with clear reproduction steps.
- Suggest enhancements — small, incremental improvements are preferred.
- Improve documentation — clarify instructions, fix typos, or add examples.
- Send pull requests (PRs) for bug fixes or new features.

#### Development Setup
Fork and clone the repo:
```
$ git clone https://github.com/selimppc/laravel-modules.git
$ cd laravel-modules
```
Install dependencies (use the latest Laravel app for testing):
```
$ composer install
```

Link this package into a local Laravel app for testing:
```
$ composer require selimppc/laravel-modules:^1.0
```
Run artisan commands in the test app to verify functionality:
```
php artisan laravel-modules:install --with-example
php artisan serve
```

#### Coding Standards
- Follow PSR-12 coding style.
- Use type hints and final classes where possible.
- Keep service providers and bootstrapping lean.
- Write small, focused commits with descriptive messages.

#### Pull Requests
- PRs should target the main branch.
- Include a description of what was changed and why.
- Add/update tests if applicable.
- Keep PRs focused (avoid mixing multiple changes).

#### Release Process (maintainers)
- Ensure all tests pass.
- Update version in composer.json if needed.
- Tag a release (e.g., v1.0.1):
```
$ git tag -a v1.0.1 -m "Bugfix release"
$ git push origin v1.0.1
```
- Packagist will auto-update via webhook.

#### Code of Conduct
Please be respectful and constructive. Follow the Contributor Covenant guidelines.

✨ Thanks again for helping make selimppc/laravel-modules better!