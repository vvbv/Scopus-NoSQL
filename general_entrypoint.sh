#!/bin/bash
echo "[UTC $(date +"%T")][Mongo]: Iniciando servidor."
mongod --fork --logpath /var/log/mongodb.log --port 27017 > /dev/null
echo "[UTC $(date +"%T")][Mongo]: Servidor iniciado."
echo "[UTC $(date +"%T")][Mongo]: Iniciando importación de documentos."
mongoimport --db proyecto_nosql --collection articles --file /dataset/mongo_dataset.json --port 27017 2> /dev/null
echo "[UTC $(date +"%T")][Mongo]: Documentos importados."
export DBA_PASSWORD=root 
echo "[UTC $(date +"%T")][Virtuoso]: Configurando servidor."
/bin/bash /entrypoint/virtuoso_entrypoint.sh 2> /dev/null
echo "[UTC $(date +"%T")][Virtuoso]: Servidor configurado."
echo "[UTC $(date +"%T")][Virtuoso]: Iniciando servidor."
virtuoso-t +wait > /dev/null
echo "[UTC $(date +"%T")][Virtuoso]: Servidor iniciado."
echo "[UTC $(date +"%T")][Virtuoso]: Iniciando importación de triplas."
isql 127.0.0.1:1111 dba root < /tools/import_vt.isql > /dev/null
echo "[UTC $(date +"%T")][Virtuoso]: Triplas importadas."
echo "[UTC $(date +"%T")][General]: Ahora puede acceder a los datos de Mongo y Virtuoso(RDF) escribiendo el comando mongo o isql respectivamente, se iniciará isql por defecto."
isql 127.0.0.1:1111 dba root
/bin/bash