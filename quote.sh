#!/bin/bash

while :
do
	php-fpm -q -f get.php
	sleep 1
done
