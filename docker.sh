#!/bin/bash

docker pull php > /dev/null
docker run --rm -v $PWD/vm:/nosql -w /nosql php /bin/bash -c "php -f formatter.php"
