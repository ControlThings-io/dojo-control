# Release checklist

1. Review [current status](docs/STATUS.md), verify the intended remaining
   vulnerabilities, and resolve release blockers. Build and test on Linux AMD64
   with rootless Podman and SELinux enabled.
2. Build the image using the README command. Record actual PHP, Apache, MariaDB,
   and base-image versions/digests with the release. The source checksums are pinned;
   The base digest is pinned; Debian package revisions can change between builds.
3. Check a fresh startup, all seed rows, the website's database reset, stop/start,
   disposable replacement, and representative existing vulnerability paths.
   Check that Snake/SOAP URLs are absent and only localhost port 8081 is published.
4. Obtain explicit authorization before publishing. Log in to `ghcr.io` using
   Podman and a GitHub identity allowed to publish the organization package.
5. Tag the validated local image with a numbered version and `stable`, then push
   both tags. For example, replacing `VERSION` with the chosen release:

   ```sh
   podman tag localhost/dojo-control:dev ghcr.io/controlthings-io/dojo-control:VERSION
   podman tag localhost/dojo-control:dev ghcr.io/controlthings-io/dojo-control:stable
   podman push ghcr.io/controlthings-io/dojo-control:VERSION
   podman push ghcr.io/controlthings-io/dojo-control:stable
   ```

6. Set the GHCR package visibility to public and link it to the repository.
   Verify an anonymous pull and the unmodified `run.sh` on a student system.
7. Record the published image digest, version, and validation evidence in the
   release, and update status. Distribute `run.sh`; explain that rerunning resets
   all progress. Publishing an image does not commit or push repository changes.
