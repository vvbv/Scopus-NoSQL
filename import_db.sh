#!/bin/bash

mongod --fork --logpath /var/log/mongodb.log --port 27017
cd /dataset
mongoimport --db proyecto_nosql --collection articles --file mongo_dataset.json --port 27017
cd /
mongo 127.0.0.1:27017/proyecto_nosql
