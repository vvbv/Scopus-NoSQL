#!/bin/bash

mongod --fork --logpath /var/log/mongodb.log --port 27017
cd /data
mongoimport --db proyecto_nosql --collection articles --file mongo_dataset.json --port 27017
cd /
mongo --port 27017 0.0.0.0/proyecto_nosql
