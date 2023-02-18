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
  FOREIGN KEY (`recipeID`) REFERENCES recipe (`recipeID`) ON DELETE CASCADE);

-- -----------------------------------------------------
-- Table raw
-- -----------------------------------------------------
CREATE TABLE `raw` (
  `rawID` INT NOT NULL,
  `rawName` VARCHAR(45) NULL,
  PRIMARY KEY (`rawID`));

-- -----------------------------------------------------
-- Table ingredient
-- -----------------------------------------------------
CREATE TABLE `ingredient` (
  `ingredientID` INT NOT NULL,
  `measurement` INT NULL,
  `unit` SET('Tablespoon', 'Teaspoon', 'Cup', 'Celcius', 'Fahrenheit', 'Milliliter', 'Pound', 'Gram', 'Kilogram', 'Liter', 'Ounce', 'Gallon', 'Pint'),
  `preparation` VARCHAR(45) NULL,
  PRIMARY KEY (`ingredientID`));

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
  FOREIGN KEY (`recipeID`) REFERENCES recipe (`recipeID`) ON DELETE CASCADE);

-- -----------------------------------------------------
-- Table `reviewed`
-- TO DO: change datatype of review once rating system implementation logic is discussed
-- -----------------------------------------------------
CREATE TABLE `reviewed` (
  `userID` INT NOT NULL,
  `recipeID` INT NOT NULL,
  `review` VARCHAR(200) NULL,
  PRIMARY KEY (`userID`, `recipeID`),
  FOREIGN KEY (`userID`) REFERENCES user (`userID`) ON DELETE CASCADE,
  FOREIGN KEY (`recipeID`) REFERENCES recipe (`recipeID`) ON DELETE CASCADE);

-- -----------------------------------------------------
-- Table `inPantry`
-- -----------------------------------------------------
CREATE TABLE `inPantry` (
  `userID` INT NOT NULL,
  `rawID` INT NOT NULL,
  PRIMARY KEY (`userID`, `rawID`),
  FOREIGN KEY (`userID`) REFERENCES user (`userID`) ON DELETE CASCADE,
  FOREIGN KEY (`rawID`) REFERENCES raw (`rawID`) ON DELETE CASCADE);

-- -----------------------------------------------------
-- Table `ingredientRaw`
-- TINYINT is MYSQL boolean, 0 is false and 1 is true
-- -----------------------------------------------------
CREATE TABLE `ingredientRaw` (
  `ingredientID` INT NOT NULL,
  `rawID` INT NOT NULL,
  `substitute` TINYINT(1), 
  PRIMARY KEY (`ingredientID`, `rawID`),
  FOREIGN KEY (`ingredientID`) REFERENCES ingredient (`ingredientID`) ON DELETE CASCADE,
  FOREIGN KEY (`rawID`) REFERENCES raw (`rawID`) ON DELETE CASCADE);

-- -----------------------------------------------------
-- Table `recipeIngredient`
-- -----------------------------------------------------
CREATE TABLE `recipeIngredient` (
  `recipeID` INT NOT NULL,
  `ingredientID` INT NOT NULL,
  PRIMARY KEY (`ingredientID`, `recipeID`),
  FOREIGN KEY (`ingredientID`) REFERENCES ingredient (`ingredientID`) ON DELETE CASCADE,
  FOREIGN KEY (`recipeID`) REFERENCES recipe (`recipeID`) ON DELETE CASCADE);


-- SET SQL_MODE=@OLD_SQL_MODE;
-- SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
-- SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;