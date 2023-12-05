/*
This is the setup DB script for the project. It will create the database tables.
*/

CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    profile_photo VARCHAR(255) DEFAULT '../project/assets/images/no-profile-pic.webp'
);

CREATE TABLE LearningPaths (
    PathID INT PRIMARY KEY AUTO_INCREMENT,
    UserID INT,
    OriginalUserID INT,
    OriginalPathID INT,
    Title VARCHAR(255) NOT NULL,
    Description VARCHAR(255),
    Resources VARCHAR(255),
    FOREIGN KEY (UserID) REFERENCES users(id),
    FOREIGN KEY (OriginalUserID) REFERENCES users(id),
    FOREIGN KEY (OriginalPathID) REFERENCES LearningPaths(PathID)
);

CREATE TABLE Votes (
    VoteID INT PRIMARY KEY AUTO_INCREMENT,
    UserID INT,
    PathID INT,
    Vote ENUM('upvote', 'downvote') NOT NULL,
    FOREIGN KEY (UserID) REFERENCES users(id),
    FOREIGN KEY (PathID) REFERENCES LearningPaths(PathID)
);