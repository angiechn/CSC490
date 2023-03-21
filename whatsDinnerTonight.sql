CREATE SCHEMA IF NOT EXISTS `whatsdinner`;
USE `whatsdinner`;

-- -----------------------------------------------------
-- Table recipe
-- -----------------------------------------------------
CREATE TABLE `recipe` (
  `recipeID` INT NOT NULL,
  `recipeName` VARCHAR(45) NULL,
  `author` VARCHAR(45) NULL,
  `instructions` VARCHAR(2000) NULL,
  `notes` VARCHAR(500) NULL,
  PRIMARY KEY (`recipeID`));

-- -----------------------------------------------------
-- Table type 
-- -----------------------------------------------------
CREATE TABLE `type` (
  `recipeID` INT NOT NULL,  
  `type` SET('Entree', 'Dessert', 'Side', 'Soup'),
  PRIMARY KEY (`recipeID`, `type`),
  FOREIGN KEY (`recipeID`) REFERENCES recipe (`recipeID`) ON DELETE CASCADE ON UPDATE CASCADE);

-- -----------------------------------------------------
-- Table raw
-- -----------------------------------------------------
CREATE TABLE `raw` (
  `rawID` INT NOT NULL,
  `rawName` VARCHAR(45) NULL,
  PRIMARY KEY (`rawID`));

-- -----------------------------------------------------
-- Table substitute
-- -----------------------------------------------------
CREATE TABLE `substitute` (
  `rawID` INT NOT NULL,
  `subOf` INT NOT NULL,
  PRIMARY KEY (`rawID`),
  FOREIGN KEY (`subOf`) REFERENCES raw (`rawID`) ON DELETE CASCADE ON UPDATE CASCADE);

-- -----------------------------------------------------
-- Table ingredient
-- -----------------------------------------------------
CREATE TABLE `ingredient` (
  `recipeID` INT NOT NULL,
  `ingredientID` INT NOT NULL,
  `measurement` DOUBLE NULL,
  `unit` VARCHAR(45) NULL,
  `preparation` VARCHAR(45) NULL,
  PRIMARY KEY (`recipeID`, `ingredientID`),
  FOREIGN KEY (`recipeID`) REFERENCES recipe (`recipeID`) ON DELETE CASCADE ON UPDATE CASCADE);

-- -----------------------------------------------------
-- Table `user`
-- -----------------------------------------------------
CREATE TABLE `user` (
  `userID` INT NOT NULL,
  `username` VARCHAR(45) NULL,
  PRIMARY KEY (`userID`));

-- -----------------------------------------------------
-- Table `bookmarked`
-- -----------------------------------------------------
CREATE TABLE `bookmarked` (
  `userID` INT NOT NULL,
  `recipeID` INT NOT NULL,
  PRIMARY KEY (`userID`, `recipeID`),  
  FOREIGN KEY (`userID`) REFERENCES user (`userID`) ON DELETE CASCADE,
  FOREIGN KEY (`recipeID`) REFERENCES recipe (`recipeID`) ON DELETE CASCADE ON UPDATE CASCADE);

-- -----------------------------------------------------
-- Table `reviewed`
-- TO DO: change datatype of review once rating system implementation logic is discussed
-- -----------------------------------------------------
CREATE TABLE `reviewed` (
  `userID` INT NOT NULL,
  `recipeID` INT NOT NULL,
  `review` VARCHAR(200) NULL,
  PRIMARY KEY (`userID`, `recipeID`),
  FOREIGN KEY (`userID`) REFERENCES user (`userID`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`recipeID`) REFERENCES recipe (`recipeID`) ON DELETE CASCADE ON UPDATE CASCADE);

-- -----------------------------------------------------
-- Table `inPantry`
-- -----------------------------------------------------
CREATE TABLE `inPantry` (
  `userID` INT NOT NULL,
  `rawID` INT NOT NULL,
  PRIMARY KEY (`userID`, `rawID`),
  FOREIGN KEY (`userID`) REFERENCES user (`userID`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`rawID`) REFERENCES raw (`rawID`) ON DELETE CASCADE ON UPDATE CASCADE);

-- -----------------------------------------------------
-- Table `ingredientRaw`
-- TINYINT is MYSQL boolean, 0 is false and 1 is true
-- -----------------------------------------------------
CREATE TABLE `ingredientRaw` (
  `recID` INT NOT NULL,
  `ingID` INT NOT NULL,
  `rawID` INT NOT NULL,
  PRIMARY KEY (`recID`, `ingID`, `rawID`),
  FOREIGN KEY (`recID`, `ingID`) REFERENCES ingredient (`recipeID`,`ingredientID`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`rawID`) REFERENCES raw (`rawID`) ON DELETE CASCADE ON UPDATE CASCADE);


-- SET SQL_MODE=@OLD_SQL_MODE;
-- SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
-- SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;