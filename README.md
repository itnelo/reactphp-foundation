
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

## Installation

### Docker Swarm

This setup provides a basic service scalability using [Swarm mode](https://docs.docker.com/engine/swarm/key-concepts). 
For testing purposes, let's assume we have the following servers:

```
192.168.1.100   # our pc, manager node; haproxy
192.168.56.10   # vm, worker node; app instance
192.168.56.20   # vm, worker node; app instance
192.168.56.30   # vm, worker node; app instance
```

**Step 1**. Create a manager node (for haproxy with exposed ports):

```
# our pc
$ docker swarm init --advertise-addr 192.168.1.100:2377
```

And a few worker nodes:

```
# vm
$ docker swarm join --token JOIN_TOKEN --advertise-addr 192.168.56.10:2377 192.168.1.100:2377
```

where `JOIN_TOKEN` is a parameter obtained by `docker swarm join-token worker` on the manager node.
Repeat this action for all other worker servers in your cluster
using their own advertise addresses.

**Step 2**. TBI (labels)

**Step 3**. Clone the repository and apply stack configuration:

```
$ git clone git@github.com:itnelo/reactphp-foundation.git my-service && cd "$_"

$ cp docker-compose.stack.yml.dist docker-compose.stack.yml
```

**Step 4**. Replace `IMAGE_NAME` and `VERSION` placeholders with your image
from the desired registry. You should also adjust placement constraints
(according to **Step 1**) to ensure Swarm scheduler is able to assign tasks
to the configured nodes.

**Step 5**. TBI (starting swarm cluster)

TBI (rebalancing)

## See also

- [driftphp/driftphp](https://github.com/driftphp/driftphp) — 
If you are looking for a deeper Symfony integration, with Kernel adaptation
to async environment.

## Changelog

All notable changes to this project will be documented in [CHANGELOG.md](CHANGELOG.md).