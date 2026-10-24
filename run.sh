#!/bin/sh
# Pull the classroom image and replace the lab. Every launch deletes prior progress.
set -eu

IMAGE=ghcr.io/controlthings-io/dojo-control:stable

# Download first so a failed pull leaves the existing lab running.
podman pull "$IMAGE"
# NET_RAW lets the website's Ping exercise send ICMP packets.
podman run --detach --replace \
    --name dojo-control \
    --cap-add NET_RAW \
    --publish 127.0.0.1:8081:80 \
    "$IMAGE"

# Wait up to 60 seconds for the website and its database to respond.
for attempt in $(seq 1 60); do
    if podman exec dojo-control curl --fail --silent --max-time 2 \
        http://127.0.0.1/index.php >/dev/null; then
        echo 'Dojo Control: http://127.0.0.1:8081'
        echo 'Running this script again deletes your lab progress.'
        exit 0
    fi
    sleep 1
done

echo 'Lab startup failed. Check: podman logs dojo-control' >&2
exit 1
