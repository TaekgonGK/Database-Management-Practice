DROP table if exists Person;
DROP table if exists WhenWhere;
DROP table if exists Nobel;
DROP table if exists Prize;
DROP table if exists Affiliation;


CREATE TABLE Person(id int, givenName VARCHAR(50), familyName VARCHAR(50), gender VARCHAR(20), PRIMARY KEY (id));
CREATE TABLE WhenWhere(id int, date DATE, city VARCHAR(50), country VARCHAR(50), PRIMARY KEY (id));
CREATE TABLE Nobel(nobelnumber int, awardYear int, category VARCHAR(50), dateAwarded DATE, motivation VARCHAR(200), PRIMARY KEY (awardYear, category));
CREATE TABLE Prize(id int, nobelnumber int, prizeAmount int, sortOrder int, prizeStatus VARCHAR(20), affiliation VARCHAR(200), PRIMARY KEY (id,nobelnumber));
CREATE TABLE Affiliation(name VARCHAR(50), city VARCHAR(50), country VARCHAR(50), PRIMARY KEY (name,city,country));

LOAD DATA LOCAL INFILE './Person.del' INTO TABLE Person FIELDS TERMINATED BY '\t' OPTIONALLY ENCLOSED BY '"';
LOAD DATA LOCAL INFILE './WhenWhere.del' INTO TABLE WhenWhere FIELDS TERMINATED BY '\t' OPTIONALLY ENCLOSED BY '"';
LOAD DATA LOCAL INFILE './Nobel.del' INTO TABLE Nobel FIELDS TERMINATED BY '\t' OPTIONALLY ENCLOSED BY '"';
LOAD DATA LOCAL INFILE './Prize.del' INTO TABLE Prize FIELDS TERMINATED BY '\t' OPTIONALLY ENCLOSED BY '"';
LOAD DATA LOCAL INFILE './Affiliation.del' INTO TABLE Affiliation FIELDS TERMINATED BY '\t' OPTIONALLY ENCLOSED BY '"';