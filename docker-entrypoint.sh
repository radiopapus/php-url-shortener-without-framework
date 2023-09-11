#!/usr/bin/env bash

set -e

. ./docker/scripts/entrypoint-helpers.sh

if [[ "${1}" = "dev_server" ]]
then
    export APP_ENV=local

    Echo_Message 'Launching dev server'
    Run_Dev_Server
else
    exec "$@"
fi
