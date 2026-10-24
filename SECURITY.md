# Security policy

Dojo Control is an intentionally vulnerable application for authorized security
education. It includes exploitable application code, sample credentials, and
unsupported legacy PHP and OpenSSL versions. These weaknesses are deliberate
teaching material; this project is not suitable for production or sensitive data.

Run the disposable lab locally using the documented rootless Podman launcher.
Keep it bound to localhost and avoid privileged mode or host directory mounts.
Only test systems you own or have permission to assess. See the [README](README.md)
for setup and operating instructions.

## Report security or abuse concerns

Please [open an issue](https://github.com/ControlThings-io/dojo-control/issues)
for security questions, suspected misuse, or third-party abuse inquiries.
Include the affected image tag or digest and enough context to explain the
concern. Do not include credentials, personal information, or other sensitive data.

Expected vulnerabilities in the training website do not need individual reports.
Please do report unexpected risks involving the launcher, image build or
publication process, or host isolation. Reports will be reviewed in the context
of the project's educational purpose.
