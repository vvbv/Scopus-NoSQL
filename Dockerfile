FROM debian AS scopus

RUN apt update
RUN apt install php7.0 php7.0-json -y
RUN apt install mongodb -y
RUN mkdir -p /data/db

COPY vm/formatter.php /tools/formatter.php
COPY vm/json /data/json
COPY vm/import_db.sh /tools/import_db.sh

WORKDIR /data/
RUN php -f /tools/formatter.php
WORKDIR /
ENTRYPOINT [ "/tools/import_db.sh" ]
CMD /bin/bash
