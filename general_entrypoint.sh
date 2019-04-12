#!/bin/bash
echo "[$(date +"%T")][Mongo]: Iniciando servidor."
mongod --fork --logpath /var/log/mongodb.log --port 27017 > /dev/null
echo "[$(date +"%T")][Mongo]: Servidor iniciado."
echo "[$(date +"%T")][Mongo]: Iniciando importación de documentos."
mongoimport --db proyecto_nosql --collection articles --file /dataset/mongo_dataset.json --port 27017 2> /dev/null
echo "[$(date +"%T")][Mongo]: Documentos importados."
export DBA_PASSWORD=root 
echo "[$(date +"%T")][Virtuoso]: Configurando servidor."
/bin/bash /entrypoint/virtuoso_entrypoint.sh 2> /dev/null
echo "[$(date +"%T")][Virtuoso]: Servidor configurado."
echo "[$(date +"%T")][Virtuoso]: Iniciando servidor."
virtuoso-t +wait > /dev/null
echo "[$(date +"%T")][Virtuoso]: Servidor iniciado."
echo "[$(date +"%T")][Virtuoso]: Iniciando carga de datos (t-estimado: 5~10 min)."
isql 127.0.0.1:1111 dba root < /dataset/queries.rq > /dev/null
echo "[$(date +"%T")][Virtuoso]: Carga completada."
echo "Ahora puede acceder a los datos de Mongo y Virtuoso(RDF) escribiendo el comando mongo o isql respectivamente."
/bin/bash