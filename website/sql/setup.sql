CREATE TABLE users (
    username varchar(63) NOT NULL,
    firstName varchar(63) NOT NULL,
    lastName varchar(63) NOT NULL,
    email varchar(63) NOT NULL,
    password varchar(127) NOT NULL,
    profileImage BLOB NOT NULL,
    isAdmin boolean NOT NULL,
    PRIMARY KEY (username),
    UNIQUE KEY (email)
);

CREATE TABLE threads (
    threadId INT NOT NULL AUTO_INCREMENT,
    threadTitle varchar(255) NOT NULL,
    threadDate DATE NOT NULL,
    threadAuthor varchar(63),
    threadText varchar(8191) NOT NULL,
    PRIMARY KEY (threadId),
    FOREIGN KEY(threadAuthor) REFERENCES users(username) ON UPDATE CASCADE ON DELETE SET NULL
);

CREATE TABLE comments (
    commentId INT NOT NULL AUTO_INCREMENT,
    thread INT NOT NULL,
    commentDate DATE NOT NULL,
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

