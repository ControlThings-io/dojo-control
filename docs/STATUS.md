# Current status

## Current work: automatic GHCR publication (2026-10-23)

- Added `.github/workflows/publish.yml`: pushes to `master` or manual dispatch on
  that branch build and test Linux AMD64, then publish `stable` and a full commit
  SHA tag. Publication is limited to the original repository and uses the automatic
  GITHUB_TOKEN with contents-read/packages-write permissions.
- The workflow checks startup, four accounts/eight logs, PHP 5.6.40, and ping before
  publishing. It copies `.containerignore` to Docker's `.dockerignore`, pins the
  checkout action to a verified upstream v6 commit, and cancels superseded builds.
- Updated README, release procedure, and decisions to replace manual-only
  publication. The existing container implementation is committed as `3031822`;
  these workflow/documentation changes remain uncommitted.
- Added a root `SECURITY.md` and README link describing intentional vulnerabilities,
  local lab use, and repository issues as the contact for security and abuse
  concerns. Content, relative links, and whitespace were checked; no runtime
  changes or additional runtime tests were needed.

Validation: actionlint 1.7.12 passed; all three embedded shell steps passed Bash
syntax checks. The workflow's startup/seed/version/ping shell block passed locally
against the existing image using Podman in place of Docker and a temporary local
tag. No image rebuild was needed because runtime sources are unchanged. Local
Markdown links and whitespace were reviewed. Hosted Docker execution, package
permissions, and GHCR publication remain unverified until the workflow is pushed
and runs on GitHub. No commit, push, or publication was performed in this session.

## Container implementation (committed as 3031822)

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

Local validation performed before commit `3031822`, based on `875bdb3`, used rootless Podman
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
exercise files were added. Temporary validation containers were removed. See the README for local launch
instructions.

## Remaining validation and release work

- Review through ZAP and a real browser on the student ControlThings Platform,
  including any course-specific payloads and browser-dependent XSS/CSRF behavior.
- Commit and push the workflow to `master` when ready to enable automatic
  publication. Verify the first hosted run, set the GHCR package to public, and
  check an anonymous pull and the unmodified launcher on a student system.
  No remote publication has been verified in this session.
- Debian package revisions are resolved at build time. Record the final published
  image digest and versions for each classroom release.

See the [release checklist](../RELEASE_CHECKLIST.md) and [decisions](DECISIONS.md).
