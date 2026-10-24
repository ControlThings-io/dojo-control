#!/bin/bash
# Start the disposable lab; seed only a new database and stop both services on exit.
# Fail startup if MariaDB, seed validation, or either long-running service fails.
set -eu

install -d -o mysql -g mysql /run/mysqld /var/lib/mysql
if [ ! -d /var/lib/mysql/mysql ]; then
    mariadb-install-db --user=mysql --datadir=/var/lib/mysql --skip-test-db >/dev/null
fi
mariadbd --user=mysql --datadir=/var/lib/mysql \
    --socket=/run/mysqld/mysqld.sock --skip-networking &
db_pid=$!
web_pid=
cleanup() {
    trap - EXIT TERM INT
    if [ -n "$web_pid" ]; then kill -TERM "$web_pid" 2>/dev/null || true; fi
    kill -TERM "$db_pid" 2>/dev/null || true
    wait "$db_pid" 2>/dev/null || true
    if [ -n "$web_pid" ]; then wait "$web_pid" 2>/dev/null || true; fi
}
trap cleanup EXIT
trap 'exit 0' TERM INT

ready=false
for attempt in {1..60}; do
    if mariadb-admin --socket=/run/mysqld/mysqld.sock ping --silent >/dev/null 2>&1; then
        ready=true
        break
    fi
    kill -0 "$db_pid" 2>/dev/null || { echo 'MariaDB exited during startup.' >&2; exit 1; }
    sleep 1
done
[ "$ready" = true ] || { echo 'MariaDB startup timed out.' >&2; exit 1; }

mariadb <<'SQL'
CREATE USER IF NOT EXISTS 'dojo_control'@'localhost' IDENTIFIED BY 'letmein';
GRANT ALL PRIVILEGES ON dojo_control.* TO 'dojo_control'@'localhost';
SQL
if ! mariadb -Nse "SELECT SCHEMA_NAME FROM information_schema.SCHEMATA WHERE SCHEMA_NAME='dojo_control'" | grep -qx dojo_control; then
    # Match web-request permissions when initializing the deliberately vulnerable app.
    runuser -u www-data -- /usr/local/bin/php reset-db.php
    counts=$(mariadb -Nse 'SELECT (SELECT COUNT(*) FROM dojo_control.accounts), (SELECT COUNT(*) FROM dojo_control.blogs_table)')
    [ "$counts" = $'4\t8' ] || { echo "Unexpected seed counts: $counts" >&2; exit 1; }
fi

export APACHE_CONFDIR=/etc/apache2
. /etc/apache2/envvars
install -d "$APACHE_RUN_DIR" "$APACHE_LOCK_DIR"
apache2 -D FOREGROUND &
web_pid=$!
set +e
wait -n "$db_pid" "$web_pid"
status=$?
set -e
# An unexpected clean service exit is still a failure of the complete lab.
[ "$status" -ne 0 ] || status=1
exit "$status"
