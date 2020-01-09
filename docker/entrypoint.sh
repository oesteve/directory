#!/bin/sh

## Database migrate
./bin/console migrations:migrate -n

# Run server
exec /usr/bin/php -S 0.0.0.0:80 -t public