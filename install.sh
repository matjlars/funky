#!/usr/bin/env bash

BASEPATH=$(dirname $0)
FUNKY=$(dirname $BASEPATH)
YOURPROJECT=$FUNKY/../../..

echo $BASEPATH
echo $FUNKY
echo $YOURPROJECT
#cp -rf $FUNKY/scaffold/* $YOURPROJECT