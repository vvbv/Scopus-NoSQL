FROM openlink/virtuoso-opensource-7

RUN apt update
RUN apt install tzdata -y
RUN apt install php7.2 php7.2-json -y
RUN apt install mongodb -y
RUN mkdir -p /data/db

COPY formatter.php /tools/formatter.php
COPY graph_generator.php /tools/graph_generator.php
COPY dataset/json /dataset/json
RUN rm /virtuoso-entrypoint.sh
COPY general_entrypoint.sh /entrypoint/general_entrypoint.sh
COPY virtuoso_entrypoint.sh /entrypoint/virtuoso_entrypoint.sh

WORKDIR /dataset/
RUN php -f /tools/graph_generator.php
WORKDIR /
ENTRYPOINT [ "/entrypoint/general_entrypoint.sh" ]

CMD /bin/bash
