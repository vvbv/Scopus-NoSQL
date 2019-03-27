#!/bin/bash

mongod --fork --logpath /var/log/mongodb.log --port 27017
mongoimport --db proyecto_nosql --collection articles --file /dataset/mongo_dataset.json --port 27017
export DBA_PASSWORD=root 
/bin/bash /entrypoint/virtuoso_entrypoint.sh 
virtuoso-f
isql 127.0.0.1:1111 dba root < /dataset/graph.rq
/bin/bash