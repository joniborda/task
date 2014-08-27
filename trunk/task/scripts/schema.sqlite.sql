DROP DATABASE servicio_tecnico;
CREATE database servicio_tecnico;
USE servicio_tecnico;

CREATE TABLE user (
    id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(32) NOT NULL DEFAULT 'noemail@test.com',
    password VARCHAR(32) NULL,
    register_date DATETIME NOT NULL,
    user_name VARCHAR(32) NOT NULL,
    ultimo_login TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    is_admin BOOLEAN NOT NULL
);

CREATE UNIQUE INDEX user_name ON user (user_name);

CREATE TABLE comment (
    id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
    user_id VARCHAR(32) NOT NULL,
    comment TEXT NULL,
    created DATETIME NOT NULL
);

CREATE TABLE reply (
	id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
    user_id VARCHAR(32) NOT NULL,
    comment_id INTEGER NOT NULL,
    comment TEXT NULL,
    created DATETIME NOT NULL
);