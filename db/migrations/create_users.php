<?php
return [
    "up" => "
        CREATE TABLE users (
            id INT NOT NULL AUTO_INCREMENT,
            nome VARCHAR(120) NOT NULL,
            email VARCHAR(80) UNIQUE NOT NULL UNIQUE,
            data_nascimento DATE NOT NULL,
            PRIMARY KEY(id)
        );
    ",

    "down" => "
        DROP TABLE users;
    "
];