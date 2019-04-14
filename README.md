# Scopus NoSQL*
_<strong><small>* Repositorio base para la construcción de una imagen de docker.</small></strong>_  
<hr>
El siguiente repositorio contiene todo lo necesario para la construcción de una imagen de docker, la cual va a contener una base de datos almacenada en <strong>MongoDB</strong>, y un grafo RDF almacenado en <strong>Virtuoso-Opensource</strong> con los metadatos formateados de inicialmente 3000 artículos que hablan sobre el cancer, publicados en <strong>Scopus.com</strong> entre los años 2017 y 2019. 
<br>
<br>  

- <strong>Nombre de la base de datos:</strong> proyecto_nosql  
- <strong>Nombre de la colección:</strong> articles  
- <strong>Nombre del grafo:</strong> articles_metadata  
<hr>

###  Descarga y ejecución [ <a href="https://hub.docker.com/r/vvbv/nosqlscopus">dockerhub</a> ]:

```bash
docker run -it vvbv/nosqlscopus:v3 #Entrega 1
docker run -it vvbv/nosqlscopus:v5 #Entrega 3 (Beta)
```
<hr>  

### Ejemplo de un documento:

- <a href="https://github.com/vvbv/Scopus-NoSQL/blob/master/article_demo.json"> article_demo.json</a>

<hr>

### Pruebas [MongoDB]:

**Cantidad de artículos existentes de un determinado año:**  

- ```db.articles.find({"year":2017}).count()``` → 1000
- ```db.articles.find({"year":2018}).count()``` → 1000
- ```db.articles.find({"year":2019}).count()``` → 1000

**Artículos publicados, entre los cuales, un determinado autor ha hecho parte de este:**    

- ```db.articles.find({"authors.name": "Lv C."}).pretty()```

<hr>

### Pruebas [SPARQL]:
**Artículos en los que <strong>Lv C.</strong> ha participado como autor:**  

Consulta en 1 linea compatible con el cliente:   
- ```SPARQL PREFIX lo: <http://127.0.0.1/objects/> PREFIX lt: <http://127.0.0.1/terms/> SELECT ?articles ?title FROM <articles_metadata>  WHERE {  ?person rdf:type foaf:Person. ?person foaf:name "Lv C."^^xsd:string. ?articles lt:written_by ?person. ?articles lt:title ?title.};```  

```sparql

PREFIX lo: <http://127.0.0.1/objects/> 
PREFIX lt: <http://127.0.0.1/terms/> 

SELECT ?articles ?title FROM <articles_metadata>  WHERE { 
    ?person rdf:type foaf:Person.
    ?person foaf:name "Lv C."^^xsd:string.
    ?articles lt:written_by ?person.
    ?articles lt:title ?title.
};
```

**Artículo más citado:**   

Consulta en 1 linea compatible con el cliente:  
- ```SPARQL PREFIX lt: <http://127.0.0.1/terms/> SELECT ?title ?num_citations FROM <articles_metadata>  WHERE { { SELECT ?article ?num_citations FROM <articles_metadata> WHERE{?article lt:cited_by ?num_citations.} ORDER BY DESC( ?num_citations ) LIMIT 1}.?article lt:title ?title.};```

```sparql
PREFIX lt: <http://127.0.0.1/terms/>

SELECT ?title ?num_citations FROM <articles_metadata>  WHERE {
    {
        SELECT ?article ?num_citations FROM <articles_metadata> WHERE{
            ?article lt:cited_by ?num_citations.
        } 
        ORDER BY DESC( ?num_citations )
        LIMIT 1
    }.
    ?article lt:title ?title.
}; 
```

**Cantidad de artículos existentes del 2018:**  

Consulta en 1 linea compatible con el cliente:  
- ```SPARQL PREFIX lt: <http://127.0.0.1/terms/> SELECT count(*) FROM <articles_metadata> WHERE{?article lt:year 2018.};```

```sparql
PREFIX lt: <http://127.0.0.1/terms/>

SELECT count(*) FROM <articles_metadata> WHERE{
    ?article lt:year 2018.
} 
```

<hr>

### Presentado por:
- Jeison Cardona Gomez - 1325562-3743
- Juan Felipe Orozco Escobar - 1426244-3743
<hr>
