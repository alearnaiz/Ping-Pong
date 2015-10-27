SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

DROP SCHEMA IF EXISTS `ping-pong` ;
CREATE SCHEMA IF NOT EXISTS `ping-pong` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `ping-pong` ;

-- -----------------------------------------------------
-- Table `ping-pong`.`tournament`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ping-pong`.`tournament` ;

CREATE TABLE IF NOT EXISTS `ping-pong`.`tournament` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `date` DATETIME NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ping-pong`.`user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ping-pong`.`user` ;

CREATE TABLE IF NOT EXISTS `ping-pong`.`user` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ping-pong`.`tournament_user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ping-pong`.`tournament_user` ;

CREATE TABLE IF NOT EXISTS `ping-pong`.`tournament_user` (
  `tournament_id` INT NOT NULL,
  `user_id` INT NOT NULL,
  PRIMARY KEY (`tournament_id`, `user_id`),
  INDEX `fk_tournament_user_user_idx` (`user_id` ASC),
  INDEX `fk_tournament_user_tournament_idx` (`tournament_id` ASC),
  CONSTRAINT `fk_tournament_user_tournament`
    FOREIGN KEY (`tournament_id`)
    REFERENCES `ping-pong`.`tournament` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_tournament_user_user`
    FOREIGN KEY (`user_id`)
    REFERENCES `ping-pong`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
