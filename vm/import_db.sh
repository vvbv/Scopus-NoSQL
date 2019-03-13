#!/bin/bash

mongod --fork --logpath /var/log/mongodb.log --port 27017
cd /data
mongoimport --db proyecto_nosql --collection articles --file mongo_dataset.json --port 27017
cd /
mongo 0.0.0.0:27017/proyecto_nosql
