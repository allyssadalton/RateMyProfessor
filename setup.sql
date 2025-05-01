DROP DATABASE IF EXISTS RateMyProfessor;
CREATE DATABASE RateMyProfessor;
USE RateMyProfessor;


CREATE TABLE SiteUser(
    Email VARCHAR(100),
    Password VARCHAR(200),
    PRIMARY KEY (Email)
);

CREATE TABLE Professors(
    ProfessorID INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(100),
    Department VARCHAR(100),
    Rating DECIMAL
);

CREATE TABLE RatingsandComments(
    Ratings INT,
    StudentComment VARCHAR(200),
    Professor VARCHAR(100),
    Department VARCHAR(100),
    CreatedBy VARCHAR(100),
    PRIMARY KEY(CreatedBy, Professor)
);

INSERT INTO SiteUser(Email, Password) 
VALUES ("admin@uindy.edu", "e7cf3ef4f17c3999a94f2c6f612e8a888e5b1026878e4e19398b23bd38ec221a"),
	("user1@uindy.edu", "e7cf3ef4f17c3999a94f2c6f612e8a888e5b1026878e4e19398b23bd38ec221a"),
    ("user2@uindy.edu", "e7cf3ef4f17c3999a94f2c6f612e8a888e5b1026878e4e19398b23bd38ec221a");
    
INSERT INTO Professors(Name, Department, Rating)
VALUES ("Dr. P Talaga", "Engineering", 0),
		("Dr. J Martinez", "Engineering", 0),
        ("Dr. O Hummel", "Mathematics", 0),
        ("Dr. J Oaks", "Mathematics", 0);
        
        