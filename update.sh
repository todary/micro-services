#!/bin/bash
ABS_PATH=$(readlink -f $0)
CURRENT_DIR=$(dirname $ABS_PATH)
export PATH=$CURRENT_DIR/../..:$PATH;
cd $CURRENT_DIR;

composer dumpautoload -o

CURRENT_DIR=$(pwd)

for d in packages/skopenow/*/ ; do
    if [[ $d != *"servicemapper"* ]]; then
        cd $CURRENT_DIR/$d;
        echo $CURRENT_DIR/$d;
        composer install --no-dev || exit 1
        composer dumpautoload -o
    fi
done
