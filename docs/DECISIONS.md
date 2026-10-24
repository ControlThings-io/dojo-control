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
Automatic publication on pushes to `master` is authorized; see the workflow
decision below. No exercise files are added.

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

## Accepted: publish automatically after pushes to master

The user requested automatic GHCR publication on commit. GitHub Actions runs after
commits are pushed to `master`, plus manual dispatch on that branch. Build for
AMD64, verify startup, seed counts, PHP version, and ping before publishing `stable`
and a full commit SHA tag. Publish only from the original repository, never from
pull requests or other branches. Use the automatic GITHUB_TOKEN with contents-read
and packages-write permissions; do not require a personal access token.

Use Docker on the hosted runner, copying `.containerignore` to `.dockerignore`
before building so both builders use the same context exclusions. Keep the student
workflow on Podman. Pin checkout to a verified upstream commit. New pushes cancel
superseded builds. This replaces the earlier manual-only publication plan; it does
not authorize this agent to commit or push repository changes.
