CREATE DATABASE yiireg;

USE yiireg;
-- DROP TABLE users;

CREATE TABLE users (
         id int(10) unsigned NOT NULL AUTO_INCREMENT,
         username varchar(20) NOT NULL UNIQUE,
         password varchar(20) NOT NULL,
         email varchar(225) NOT NULL UNIQUE,
         activation varchar(225) NOT NULL,
         PRIMARY KEY (id)

       ) ENGINE=InnoDB;
