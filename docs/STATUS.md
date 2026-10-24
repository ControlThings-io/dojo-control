# Current status

## Completed (2026-10-23)

- Moved the legacy website into `web/`; removed Snake and SOAP, including the
  game navigation and high-score reset command. RSnake research references remain.
  Other website content changes are the seed apostrophe fix and corrected upload
  path string. All remaining website files were moved without content changes.
- Added a multi-stage Linux AMD64 image with Apache, PHP 5.6.40, MariaDB 10.11,
  and statically linked legacy OpenSSL. Source checksums and Debian base digest
  are pinned. Build tooling and repository metadata are excluded from the image.
- Added automatic seed initialization and validation, Unix-socket-only MariaDB,
  service shutdown handling, and stdout/stderr logs. No declared volumes.
- Added a short disposable launcher for public GHCR, localhost port 8081,
  readiness polling, and the NET_RAW capability required by the Ping exercise.
- Added README usage/ZAP guidance, release steps, and accepted decisions.

## Validation evidence

Checked the uncommitted implementation based on `875bdb3` with rootless Podman
5.8.9 on Linux AMD64, with host SELinux **Enforcing** and no labeling override.
The resulting local image is `localhost/dojo-control:dev`, image ID
`b27c9dcff045529c44d7ed982dcfb3e24601dc80861fb3d4c3edec08da56aa72`.
Observed runtime versions: PHP 5.6.40, MariaDB 10.11.19, Apache 2.4.68,
OpenSSL 1.0.2u. Image size: 541,044,484 bytes.

Passed:

- Image build, PHP syntax checks, Bash/POSIX-shell syntax checks, documentation
  links, and tooling/documentation whitespace review.
- Fresh startup with four accounts and eight shift logs, including the corrected
  apostrophe; normal login, registration, shift entry with a valid token, and view.
- Representative SQL injection, stored XSS markup preservation, cookie
  impersonation, ping, command injection, local inclusion, source disclosure,
  remote text reading, remote PHP inclusion, executable PHP upload, and explicit
  object injection with a destructor file write. These were HTTP-level checks,
  not proof of browser JavaScript execution or every historical exploit variant.
- Snake and SOAP endpoint absence, localhost-only HTTP publication, no image
  volumes, and exclusion of repository/build files from the document root.
- Stop/start preserving progress; web database reset restoring seed counts;
  replacement discarding uploads and creating a fresh database.
- Launcher run/readiness behavior using a temporary copy pointed at the local
  image (substituting local image lookup for the unpublished registry pull).
  A simulated failed pull exited before any replacement attempt.

Validation scripts/payloads were temporary files outside the repository; no
exercise files were added. Temporary validation containers were removed. A fresh review container named
`dojo-control` is running from the local image at <http://127.0.0.1:8081>.

## Remaining validation and release work

- Review through ZAP and a real browser on the student ControlThings Platform,
  including any course-specific payloads and browser-dependent XSS/CSRF behavior.
- Publish numbered and stable tags only after explicit authorization. Verify
  public package visibility, anonymous pulls, and the unmodified launcher against
  GHCR. This image has not been published yet; use the README's local build/run
  instructions until publication.
- Debian package revisions are resolved at build time. Record the final published
  image digest and versions for each classroom release.

Nothing is committed, pushed, or published. See the [release checklist](../RELEASE_CHECKLIST.md)
and [decisions](DECISIONS.md).
