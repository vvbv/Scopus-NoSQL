#!/bin/bash

mongod --fork --logpath /var/log/mongodb.log --port 27017
mongoimport --db proyecto_nosql --collection articles --file /dataset/mongo_dataset.json --port 27017
export DBA_PASSWORD=root 
/bin/bash /entrypoint/virtuoso_entrypoint.sh 
virtuoso-t +wait
echo "Iniciando carga de datos a virtuoso, esta operación puede tardar unos minutos."
isql 127.0.0.1:1111 dba root < /dataset/queries.rq > /dev/null
echo "Carga terminada, ahora puede acceder a los datos de Mongo y Virtuoso(RDF) escribiendo el comando mongo o isql respectivamente."
/bin/bash