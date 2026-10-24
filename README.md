# Dojo Control

An intentionally vulnerable PHP website for local security training. The disposable
image contains Apache, PHP 5.6.40, and MariaDB 10.11. It targets Linux AMD64 and
rootless Podman on ControlThings Platform (based on Fedora Aurora).

## Run the classroom image

The image must be published before these commands will work. From a checkout:

```sh
./run.sh
```

You can also distribute `run.sh` by itself. It requires Podman and a POSIX shell.
The script pulls `ghcr.io/controlthings-io/dojo-control:stable`, replaces the
previous lab, and waits for startup. Open <http://127.0.0.1:8081>.
An example seeded login is `admin` / `Flynn`.

**Every run of the script deletes the previous lab's database changes, uploads,
and other container files.** There are no persistent volumes. A failed image pull
leaves the existing container intact. The database is initialized automatically.
The website's Reset DB link resets database contents, but does not remove uploads.

```sh
podman stop dojo-control       # Stop the lab.
podman logs dojo-control       # Diagnose startup or application errors.
podman rm -f dojo-control      # Remove the lab and its progress.
```

Keep this intentionally vulnerable, unsupported PHP runtime local. Run without
`sudo`, privileged mode, host networking, or host directory mounts. Only HTTP is
published, bound to IPv4 localhost; MariaDB uses an internal Unix socket. Leave
host SELinux enabled. No container labeling override is needed by default. The launcher adds only the
`NET_RAW` capability needed by the Ping exercise.

## Use ZAP

Leave ZAP's proxy on `localhost:8080` and use `http://127.0.0.1:8081` as the target.
Use a browser launched from ZAP, or configure your browser to proxy loopback
traffic (many browsers bypass localhost by default). Confirm the target requests
appear in ZAP's History. See [ZAP's localhost guidance](https://www.zaproxy.org/faq/how-do-you-configure-zap-to-test-an-application-on-localhost/).

## Build locally

```sh
podman build --platform linux/amd64 -t localhost/dojo-control:dev .
podman run -d --replace --name dojo-control \
    --cap-add NET_RAW -p 127.0.0.1:8081:80 localhost/dojo-control:dev
podman logs -f dojo-control
```

Wait for Apache to start, then visit the website. The build compiles checksum-checked
PHP 5.6.40 and its legacy OpenSSL dependency; students do not compile anything.
Debian Bookworm supplies Apache and MariaDB 10.11. Distribution package revisions
are resolved at build time; numbered published images preserve class environments.

Website files live in `web/`, copied to `/var/www/html` without changing their URLs.
`container/` holds runtime settings and startup logic. The build context excludes
repository metadata and development files. The web tree deliberately retains
exposed configuration, sample passwords, and vulnerable code for the exercises.
Snake and SOAP have been removed.

See [release steps](RELEASE_CHECKLIST.md), [current validation and remaining work](docs/STATUS.md),
and [architecture decisions](docs/DECISIONS.md).
