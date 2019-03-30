# Scopus NoSQL*
_<strong><small>* Repositorio base para la construcción de la imagen.</small></strong>_  
<hr>
El siguiente repositorio contiene todo lo necesario para la construcción de una imagen de docker, la cual va a contener una base de datos almacenada en <strong>MongoDB</strong> con los metadatos formateados de inicialmente 3000 artículos que hablan sobre el cancer, publicados en <strong>Scopus.com</strong> entre los años 2017 y 2019. 
<br>
<br>  

- <strong>Nombre de la base de datos:</strong> proyecto_nosql  
- <strong>Nombre de la colección:</strong> articles  

<hr>

###  Descarga y ejecución [ <a href="https://hub.docker.com/r/vvbv/nosqlscopus">dockerhub</a> ]:

```bash
docker run -it vvbv/nosqlscopus:v3 #Entrega 1
```
<hr>  

### Ejemplo de un documento:

- <a href="https://github.com/vvbv/Scopus-NoSQL/blob/master/article_demo.json"> article_demo.json</a>

<hr>

### Pruebas [MongoDB]:

Cantidad de artículos publicados en un determinado año:

- ```db.articles.find({"year":2017}).count() ``` → 1000
- ```db.articles.find({"year":2018}).count()``` → 1000
- ```db.articles.find({"year":2019}).count()``` → 1000

Artículos publicados, entre los cuales, un 
determinado autor ha hecho parte de este:

- ```db.articles.find({"authors.name": "Lv C."}).pretty()```

<hr>

### Pruebas [SPARQL]:

```sparql
PREFIX lo: <http://127.0.0.1/objects/> 
PREFIX lt: <http://127.0.0.1/terms/> 

SELECT ?articles ?title FROM <articles_metadata>  WHERE { 
 ?person rdf:type foaf:Person.
 ?person foaf:name "Lv C.".
 ?articles lt:written_by ?person.
 ?articles lt:title ?title.
};
```
**Consulta en 1 linea compatible con isql**  
```SPARQL SPARQL PREFIX lo: <http://127.0.0.1/objects/> PREFIX lt: <http://127.0.0.1/terms/> SELECT ?articles ?title FROM <articles_metadata>  WHERE {  ?person rdf:type foaf:Person. ?person foaf:name "Lv C.". ?articles lt:written_by ?person. ?articles lt:title ?title.};```


<hr>

### Presentado por:
- Juan Felipe Orozco Escobar - 1426244-3743
- Jeison Cardona Gomez - 1325562-3743

<hr>
