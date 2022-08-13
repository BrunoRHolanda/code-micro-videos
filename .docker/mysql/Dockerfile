FROM mysql:5.7

COPY ./initdb.sql /docker-entrypoint-initdb.d/initdb.sql

RUN chmod a+x /docker-entrypoint-initdb.d/initdb.sql

RUN usermod -u 1000 mysql
