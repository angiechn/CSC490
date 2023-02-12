-- change name of database from mydb to whatsdinner
DROP DATABASE IF EXISTS `mydb`;
CREATE SCHEMA IF NOT EXISTS `whatsdinner`;
USE `whatsdinner`;

-- -----------------------------------------------------
-- Table recipe
-- -----------------------------------------------------
CREATE TABLE `recipe` (
  `recipeID` INT NOT NULL,
  `recipeName` VARCHAR(45) NULL,
  `author` VARCHAR(45) NULL,
  `instructions` VARCHAR(1000) NULL,
  `notes` VARCHAR(200) NULL,
  PRIMARY KEY (`recipeID`));

-- -----------------------------------------------------
-- Table type 
-- -----------------------------------------------------
CREATE TABLE `type` (
  `recipeID` INT NOT NULL,  
  `type` SET('Entree', 'Dessert', 'Side'),
  PRIMARY KEY (`recipeID`, `type`),
  FOREIGN KEY (`recipeID`) REFERENCES recipe (`recipeID`));

-- -----------------------------------------------------
-- Table raw
-- -----------------------------------------------------
CREATE TABLE `rawIngredient` (
  `rawIngredientID` INT NOT NULL,
  `rawIngredientName` VARCHAR(45) NULL,
  PRIMARY KEY (`rawIngredientID`));

-- -----------------------------------------------------
-- Table ingredient
-- -----------------------------------------------------
CREATE TABLE `ingredient` (
  `recipeID` INT NOT NULL,
  `ingredientID` INT NOT NULL,
  `measurement` INT NULL,
  `unit` SET('tbsp', 'tsp', 'fl. oz', 'c', 'ml', 'lb', 'F', 'C', 'g', 'kg', 'l', 'oz', 'gal', 'pt'),
  PRIMARY KEY (`ingredientID`),
  FOREIGN KEY (`recipeID`) REFERENCES recipe (`recipeID`));

-- -----------------------------------------------------
-- Table `user`
-- -----------------------------------------------------
CREATE TABLE `user` (
  `userID` INT NOT NULL,
  `password` VARCHAR(45) NULL,
  PRIMARY KEY (`userID`));

-- -----------------------------------------------------
-- Table `substitutes`
-- -----------------------------------------------------
CREATE TABLE `substitutes` (
  `rawIngredientID` INT NOT NULL,
  `substituteID` INT NOT NULL,
  PRIMARY KEY (`rawIngredientID`, `substituteID`),
  FOREIGN KEY (`rawIngredientID`) REFERENCES rawIngredient (`rawIngredientID`));

-- -----------------------------------------------------
-- Table `favorited`
-- -----------------------------------------------------
CREATE TABLE `favorited` (
  `userID` INT NOT NULL,
  `recipeID` INT NOT NULL,
  PRIMARY KEY (`userID`, `recipeID`),  
  FOREIGN KEY (`userID`) REFERENCES user (`userID`),
  FOREIGN KEY (`recipeID`) REFERENCES recipe (`recipeID`));

-- -----------------------------------------------------
-- Table `reviewed`
-- -----------------------------------------------------
CREATE TABLE `reviewed` (
  `userID` INT NOT NULL,
  `recipeID` INT NOT NULL,
  `review` VARCHAR(200) NULL,
  PRIMARY KEY (`userID`, `recipeID`),
  FOREIGN KEY (`userID`) REFERENCES user (`userID`),
  FOREIGN KEY (`recipeID`) REFERENCES recipe (`recipeID`));

-- -----------------------------------------------------
-- Table `has`
-- -----------------------------------------------------
CREATE TABLE `has` (
  `userID` INT NOT NULL,
  `rawIngredientID` INT NOT NULL,
  PRIMARY KEY (`userID`, `rawIngredientID`),
  FOREIGN KEY (`userID`) REFERENCES user (`userID`),
  FOREIGN KEY (`rawIngredientID`) REFERENCES rawIngredient (`rawIngredientID`));

-- -----------------------------------------------------
-- Table `contains`
-- -----------------------------------------------------
CREATE TABLE `contains` (
  `ingredientID` INT NOT NULL,
  `rawIngredientID` INT NOT NULL,
  PRIMARY KEY (`ingredientID`, `rawIngredientID`),
  FOREIGN KEY (`ingredientID`) REFERENCES ingredient (`ingredientID`),
  FOREIGN KEY (`rawIngredientID`) REFERENCES rawIngredient (`rawIngredientID`));

-- SET SQL_MODE=@OLD_SQL_MODE;
-- SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
-- SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;