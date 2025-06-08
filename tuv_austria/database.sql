CREATE DATABASE IF NOT EXISTS 4628195_austria;
USE 4628195_austria;

CREATE TABLE cards (
    id INT NOT NULL AUTO_INCREMENT,
    data_no VARCHAR(255) NOT NULL UNIQUE,
    card_no VARCHAR(255),
    name VARCHAR(255),
    id_no VARCHAR(255),
    issue_date DATE,
    expiry_date DATE,
    company VARCHAR(255),
    model VARCHAR(255),
    ref_no VARCHAR(255),
    issuance_no VARCHAR(255),
    certification VARCHAR(255),
    assessor VARCHAR(255),
    cr_number VARCHAR(255),
    photo VARCHAR(255),
    PRIMARY KEY (id)
);

CREATE TABLE admin_users (
    id INT NOT NULL AUTO_INCREMENT,
    username VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    PRIMARY KEY (id)
);

INSERT INTO admin_users (username, password_hash) VALUES ('admin', SHA2('admin123', 256));
