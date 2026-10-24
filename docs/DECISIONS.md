# Decisions

## Accepted: disposable legacy lab

Preserve PHP 5.6.40 and the remaining intentionally vulnerable application behavior.
Use MariaDB 10.11 rather than migrating queries and SQL injection lessons to SQLite.
Remove unused SOAP endpoints/library and the Flash-based Snake game. Fix malformed
seed SQL. These are user-approved choices, not a PHP modernization project.

## Accepted: one image and a short launcher

Bundle Apache, PHP, and MariaDB so students need only rootless Podman and `run.sh`.
Target ControlThings Platform / Fedora Aurora on Linux AMD64. Publish to public
GHCR at `ghcr.io/controlthings-io/dojo-control`, with `stable` and numbered releases.
Publication requires separate explicit authorization. No exercise files are added.

Each launcher run pulls first and replaces the container, discarding all progress.
Use no volumes. Bind HTTP to `127.0.0.1:8081`, reserving 8080 for ZAP. Keep SELinux
and ordinary container isolation; revisit labeling only if a demonstrated exercise
conflict requires it. Do not disable SELinux system-wide. Validation showed that Podman drops `NET_RAW`
by default and the existing Ping exercise needs it; add that capability alone.

## Accepted: separate repository tooling from the website

Keep the legacy website together in `web/`, copied to Apache's document root.
This preserves URLs, relative includes, source browsing, and intentionally exposed
files. Keep container tooling outside that tree. See [README](../README.md).

## Implementation: build legacy PHP against Bookworm's runtime

Compile upstream PHP 5.6.40 and statically link checksum-checked OpenSSL 1.0.2u;
use Bookworm's MariaDB 10.11 and Apache. This avoids using an obsolete database
from an archived PHP distribution. Both legacy PHP and OpenSSL are intentionally
unsupported lab dependencies. The Debian base digest is pinned; package revisions float at build
time; published image digests provide the classroom artifact identity.
