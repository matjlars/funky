#!/usr/bin/env bash

FUNKY=$(dirname $0)
MISTERMASHU=$(dirname $FUNKY)
VENDOR=$(dirname $MISTERMASHU)
YOURPROJECT=$(dirname $VENDOR)

echo $FUNKY
echo $YOURPROJECT

#cp -rf $FUNKY/scaffold/* $YOURPROJECT