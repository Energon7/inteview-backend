#!/bin/bash

FILE_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

if [[ "$(uname -s)" == Darwin* ]] ;
then
    export DOCKER_DEFAULT_PLATFORM=linux/x86_64
fi

pushd "$FILE_DIR"

bash rebuild.sh

popd
