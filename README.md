
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
192.169.56.1    # our pc, manager node; haproxy
192.169.56.10   # vm, worker node; app instance
192.169.56.20   # vm, worker node; app instance
192.169.56.30   # vm, worker node; app instance
```

**Pre-requirements**. Ensure all necessary ports are accessible on each machine
or it may cause implicit problems during communication between nodes.
A common symptom is a successful `nslookup` for all replicas within overlay,
but a failed `ping` of some of them. Here is a quick list:

```
$ sudo ufw status
Status: active

To                         Action      From
--                         ------      ----
22/tcp                     ALLOW       192.169.56.0/24
2376/tcp                   ALLOW       192.169.56.0/24
2377/tcp                   ALLOW       192.169.56.0/24
4789/udp                   ALLOW       192.169.56.0/24
7946/tcp                   ALLOW       192.169.56.0/24
7946/udp                   ALLOW       192.169.56.0/24
```

**Limitations**. This setup assumes you are using a single haproxy instance,
on the fixed node in the cluster and only that node will have its ports published:

\*\*image\*\*

**Step 1**. Create a manager node (for haproxy with exposed ports):

```
# our pc
$ docker swarm init --advertise-addr 192.169.56.1
```

And a few worker nodes:

```
# vm
$ docker swarm join --token JOIN_TOKEN --advertise-addr 192.169.56.10 192.169.56.1
```

where `JOIN_TOKEN` is a parameter obtained by `docker swarm join-token worker` on the manager node.
Repeat this action for all other worker servers in your cluster
using their own advertise addresses.

**Step 2**. Assign geography labels to be able to evenly distribute
containers between all available servers:

```
# our pc
$ docker node update --label-add provider_location_machine=do.fra1.d1 HOSTNAME
```

where `HOSTNAME` is a server identifier, see `docker node ls` on the manager node.

**Step 3**. Clone the repository and apply stack configuration:

```
# our pc
$ git clone git@github.com:itnelo/reactphp-foundation.git my-service && cd "$_"
$ cp docker-compose.stack.yml.dist docker-compose.stack.yml
```

Replace `IMAGE_NAME` and `VERSION` placeholders with your image
from the desired registry. You should also adjust placement constraints
(according to **Step 2**) to ensure Swarm scheduler is able to assign tasks
to the configured nodes.

You may also need to check `haproxy.stack.cfg` from the `docker` directory
if you have changed some ports for your environment or just use a custom haproxy image.

**Step 4**. Deploy services:

```
# our pc
$ docker stack deploy --orchestrator swarm --compose-file docker-compose.stack.yml my-service
```

By accessing `192.169.56.1:6637/stats` (if you stick to the suggested default configuration)
you should now see a rendered page with haproxy statistics for your backend:

\*\*image\*\*

\*\*TBD: rebalancing\*\*

## See also

- [driftphp/driftphp](https://github.com/driftphp/driftphp) — 
If you are looking for a deeper Symfony integration, with Kernel adaptation
to async environment.
- [thesecretlivesofdata.com/raft](http://thesecretlivesofdata.com/raft/) —
A helpful visualization to understand how the distributed consensus algorithm,
used by Docker Swarm, works.

## Changelog

All notable changes to this project will be documented in [CHANGELOG.md](CHANGELOG.md).