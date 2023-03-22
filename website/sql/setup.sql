CREATE DATABASE cosc360project;

USE cosc360project;

CREATE USER IF NOT EXISTS 'cosc360user'@'localhost' IDENTIFIED BY '1234';
GRANT ALL PRIVILEGES ON cosc360project.* TO 'cosc360user'@'localhost';
CREATE USER IF NOT EXISTS 'cosc360user'@'%' IDENTIFIED BY '1234';
GRANT ALL PRIVILEGES ON cosc360project.* TO 'cosc360user'@'%';


CREATE TABLE users (
    username varchar(63) NOT NULL,
    firstName varchar(63),
    lastName varchar(63),
    email varchar(63) NOT NULL,
    password varchar(127) NOT NULL,
    profileImage BLOB,
    isAdmin boolean NOT NULL DEFAULT 0,
    PRIMARY KEY (username),
    UNIQUE KEY (email)
);

CREATE TABLE threads (
    threadId INT NOT NULL AUTO_INCREMENT,
    threadTitle varchar(255) NOT NULL,
    threadDate DATETIME NOT NULL,
    lastPost DATETIME NOT NULL,
    threadAuthor varchar(63),
    threadText varchar(8191) NOT NULL,
    PRIMARY KEY (threadId),
    FOREIGN KEY(threadAuthor) REFERENCES users(username) ON UPDATE CASCADE ON DELETE SET NULL
);

CREATE TABLE comments (
    commentId INT NOT NULL AUTO_INCREMENT,
    thread INT NOT NULL,
    commentDate DATETIME NOT NULL,
    commentAuthor varchar(63),
    commentText varchar(8191),
    PRIMARY KEY (commentId),
    FOREIGN KEY (commentAuthor) REFERENCES users(username) ON UPDATE CASCADE ON DELETE SET NULL,
    FOREIGN KEY (thread) REFERENCES threads(threadId) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE `images`(
    imageId INT NOT NULL AUTO_INCREMENT,
    image BLOB NOT NULL,
    PRIMARY KEY (imageId)
);
