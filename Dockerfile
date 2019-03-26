FROM debian AS scopus

COPY vt-pass.txt /root/vt-pass.txt

RUN apt update
RUN apt install php7.0 php7.0-json -y
RUN apt install mongodb -y
RUN apt-get install apt-transport-https ca-certificates curl gnupg2 software-properties-common -y
RUN curl -fsSL https://download.docker.com/linux/debian/gpg | apt-key add -
RUN apt-key fingerprint 0EBFCD88
RUN add-apt-repository "deb [arch=amd64] https://download.docker.com/linux/debian $(lsb_release -cs) stable"
RUN apt update
RUN apt-get install docker-ce docker-ce-cli containerd.io -y
RUN docker run --name virtuoso_server --interactive --tty --env DBA_PASSWORD=root --publish 1111:1111 --publish 8890:8890 openlink/virtuoso-opensource-7:latest
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
