#!/usr/bin/env bash

FUNKY=$(dirname $0)
MISTERMASHU=$(dirname $FUNKY)
VENDOR=$(dirname $MISTERMASHU)
YOURPROJECT=$(dirname $VENDOR)

cp -rf $FUNKY/scaffold/* $YOURPROJECT
mv _gitignore .gitignore