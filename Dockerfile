FROM debian AS scopus

RUN apt update
RUN apt install php7.0 php7.0-json -y
RUN apt install mongodb -y
RUN apt install virtuoso-opensource -y
RUN mkdir -p /data/db

COPY formatter.php /tools/formatter.php
COPY graph_generator.php /tools/graph_generator.php
COPY dataset/json /dataset/json
COPY import_db.sh /tools/import_db.sh

WORKDIR /dataset/
RUN php -f /tools/formatter.php
WORKDIR /
ENTRYPOINT [ "/tools/import_db.sh" ]
CMD /bin/bash
