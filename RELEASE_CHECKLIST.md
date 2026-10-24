# Release checklist

## Automatic classroom publication

1. Review [current status](docs/STATUS.md) and validate relevant existing exercises
   locally with rootless Podman and SELinux enabled. The workflow's startup checks
   do not establish browser/ZAP compatibility or every exploit's behavior.
2. Commit and push changes to `master` when ready to publish. Local commits do not
   trigger GitHub Actions. The [workflow](.github/workflows/publish.yml) builds
   Linux AMD64, checks startup, four seed accounts, eight shift logs, PHP 5.6.40,
   and ping, then publishes `sha-<full commit SHA>` followed by `stable`.
   Other branches and pull requests do not publish. Manual dispatch is available
   on `master`. GitHub Actions and organization package creation must be allowed.
3. Check the Actions result and image push digests. Do not infer publication from
   a successful local build. The automatic GITHUB_TOKEN provides package access;
   no personal access token is required. If the package already exists, confirm
   this repository has Actions access to it.
4. After the first publication, set the GHCR package visibility to **public** and
   verify its repository association. Verify an anonymous pull and the unmodified
   `run.sh` on a student system. The launcher replaces the lab and deletes progress.
5. For a fixed classroom release, record the published digest, source commit,
   runtime versions, and validation evidence. Source checksums and the Debian base
   digest are pinned, but Debian package revisions can change on subsequent builds
   of the same commit. Use a digest for an exact image or tag that verified digest
   with a chosen numbered version. Do not rebuild merely to add a release tag.

## Optional numbered tag

After selecting and pulling the verified image digest, replace `DIGEST` and
`VERSION` below with the published digest and chosen release version:

```sh
podman pull ghcr.io/controlthings-io/dojo-control@sha256:DIGEST
podman tag ghcr.io/controlthings-io/dojo-control@sha256:DIGEST \
    ghcr.io/controlthings-io/dojo-control:VERSION
podman push ghcr.io/controlthings-io/dojo-control:VERSION
```

Manual pushes require a GitHub identity allowed to publish this package. The
workflow manages `stable`; avoid manually overwriting it. Changes remain
uncommitted unless explicitly requested, and commit permission does not authorize
an agent to push.
