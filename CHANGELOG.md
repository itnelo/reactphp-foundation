# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]
### Changed

- No changes yet.

## [0.3.0] - 2020-08-17
### Added

- `Uri\MatcherInterface` and `ArrayMatcher` to map URI path from requests
to the handler's actions (a simplified version of `UrlMatcher` from
[symfony/routing](https://github.com/symfony/routing/blob/v5.1.0/Matcher/UrlMatcher.php)).
- `.dockerignore` to exclude unnecessary/insecure files from the image building context.
- Test suite configuration for [PHPUnit](https://github.com/sebastianbergmann/phpunit).
- "Tests" block and navigation links for README.md.

### Changed

- Container configuration logic has moved from the application entrypoint
to the `YamlConfigurator` shortcut.
- Using HTTP health checks for HAProxy instance (instead of TCP).

### Fixed

- Definition options from `_defaults` node in the `services.yml` are now properly
picked up by all services, not only ones defined in the same file (works if that node
doesn't already exists in the target definition file; revised
`YamlFileLoader` in the `Bridge\Symfony` scope).
- Explicit UIDs and permissions for users within Dockerfiles
(preventing potential access issues during CI checks and other workflows).

## [0.2.0] - 2020-07-20
### Added

- Shutdown service that gracefully terminates the event loop
after corresponding signals are received (using `ext-pcntl`). 
- Docker-compose configuration for local development.
- Docker stack configuration for [Swarm mode](https://docs.docker.com/engine/swarm/key-concepts),
installation guide in README.md.
- [HAProxy](https://www.haproxy.com) configuration to balance requests
between multiple application replicas within docker network using built-in DNS service.

## [0.1.0] - 2020-06-22
### Added

- Entrypoint to build a DI container by service definitions,
with `.env` and `parameters.yml` support.
- Main `Application` class that starts
the async socket server and the event loop.
- `ServerInterface` and the `HandlerInterface`, example handler implementation.
- Non-blocking logger (based on [wyrihaximus/react-psr-3-stdio](https://github.com/WyriHaximus/reactphp-psr-3-stdio)).

[Unreleased]: https://github.com/itnelo/reactphp-foundation/compare/0.3.0...0.x
[0.3.0]: https://github.com/itnelo/reactphp-foundation/compare/0.2.0..0.3.0
[0.2.0]: https://github.com/itnelo/reactphp-foundation/compare/0.1.0..0.2.0
[0.1.0]: https://github.com/itnelo/reactphp-foundation/releases/tag/0.1.0
