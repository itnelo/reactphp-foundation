
# ReactPHP Foundation

This skeleton for self-sufficient, asynchronous microservice contains:

- Interfaces
    - `psr/log` — PSR-3 (Logger)
    - `psr/http-message` — PSR-7 (HTTP Message)
    - `psr/container` — PSR-11 (Container)
- Decoupling code via Symfony's container
    - `symfony/dependency-injection`
- Ensure the non-blocking I/O with ReactPHP components
    - `react/event-loop`
    - `react/http`
    - `react/stream`
- Managing environment and configurations
    - `symfony/dotenv`
    - `symfony/config`
    - `symfony/yaml`

It follows strong SOLID design and fully PSR-compatible, 
with PHP 7.4+ features in mind
(starting with typed properties). 

It is also relatively lightweight and takes benefits
from both [Symfony](https://github.com/symfony/symfony) components
and [ReactPHP](https://github.com/reactphp/reactphp)
without raising up a heavy artillery setup.

## See also

- [driftphp/driftphp](https://github.com/driftphp/driftphp) — 
If you are looking for a deeper Symfony integration, with Kernel adaptation
to async environment.

## Changelog

All notable changes to this project will be documented in [CHANGELOG.md](CHANGELOG.md).