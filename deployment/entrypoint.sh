#!/usr/bin/env bash
set -e

initialStuff() {
    php artisan event:cache; \
    php artisan config:cache; \
    php artisan route:cache;
}

# TODO : Düzenlenmeli
#if [ $# -gt 0 ]; then
#    exec gosu $WWWUSER "$@"
#else
#    /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
#fi

if [ "$1" != "" ]; then
    exec "$@"
else
    initialStuff
    exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
fi

