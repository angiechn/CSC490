-- change name of database from mydb to whatsdinner
DROP DATABASE IF EXISTS `mydb`;
CREATE SCHEMA IF NOT EXISTS `whatsdinner`;
USE `whatsdinner` ;

-- -----------------------------------------------------
-- Table recipe
-- -----------------------------------------------------
CREATE TABLE `recipe` (
  `recipeID` INT NOT NULL,
  `recipeName` VARCHAR(45) NULL,
  `author` VARCHAR(45) NULL,
  `instructions` VARCHAR(1000) NULL,
  `notes` VARCHAR(200) NULL,
  `type` SET('Entree', 'Dessert', 'Side'),
  PRIMARY KEY (`recipeID`));
-- -----------------------------------------------------
-- Table ingredient
-- -----------------------------------------------------
CREATE TABLE `ingredient` (
  `ingredientID` INT NOT NULL,
  `measurement` INT NULL,
  `unit` INT NULL,
  PRIMARY KEY (`recipeID`, `ingredientID`, `rawID`),
  FOREIGN KEY (`recipeID`) REFERENCES recipe (`recipeID`),
  FOREIGN KEY (`rawID`) REFERENCES raw (`rawID`));

-- -----------------------------------------------------
-- Table rawIngredient
-- -----------------------------------------------------
CREATE TABLE `rawIngredient` (
  `rawIngID` INT NOT NULL,
  `rawIngName` VARCHAR(45) NULL,
  PRIMARY KEY (`rawIngID`));
-- -----------------------------------------------------
-- Table `user`
-- -----------------------------------------------------
CREATE TABLE `user` (
  `userID` INT NOT NULL,
  `password` VARCHAR(45) NULL,
  PRIMARY KEY (`userID`));

-- SET SQL_MODE=@OLD_SQL_MODE;
-- SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
-- SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
