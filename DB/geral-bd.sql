SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

CREATE SCHEMA IF NOT EXISTS `gas-inspector-tracker` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `gas-inspector-tracker`;

-- -----------------------------------------------------
-- Table `gas-inspector-tracker`.`users`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `gas-inspector-tracker`.`users` (
  `id` INT(10) NOT NULL ,
  `name` VARCHAR(45) NOT NULL COMMENT 'User real name (May include spaces)' ,
  `username` VARCHAR(45) NOT NULL COMMENT 'User username (letters, numbers and dots allowed)' ,
  `password` VARCHAR(100) NOT NULL COMMENT 'User password (hash)' ,
  --`gender` INT(1) NOT NULL COMMENT 'User type (one digit)(1 - male, 2 - female)' ,
  --`country` VARCHAR(45) NULL COMMENT 'User country - https://www.worldometers.info/geography/how-many-countries-are-there-in-the-world/' ,
  --`city` VARCHAR(45) NULL COMMENT 'User city' ,
  --`birthdate` DATE NULL COMMENT 'User birthdate (data)' ,
  `type` INT(1) NOT NULL COMMENT 'User type (one digit)(1 - normal, 2 - admin)' ,
  `photo_path` VARCHAR(250) NULL COMMENT 'User photo path' ,
  `photo_path_aux` VARCHAR(250) NULL COMMENT 'Caminho da foto do utilizador auxiliar para código' ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Create default admin user
-- -----------------------------------------------------
INSERT INTO `gas-inspector-tracker`.`users` (`id`,`name`,`username`,`password`,`type`) VALUES (1,'Administrator','admin','d31e6a23d06bb2ca18ad612a596be36183b8e302ba3aa583384e305b279ab9e7',2);

-- -----------------------------------------------------
-- Table `gas-inspector-tracker`.`clients`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `gas-inspector-tracker`.`clients` (
  `id` INT(10) NOT NULL ,
  `user_id` INT(10) NOT NULL COMMENT 'ID de utilizador que criou cliente (até 10 digitos)' ,
  `nome` VARCHAR(45) NULL COMMENT 'Nome completo (pode incluir espaços)' ,
  `nif` INT(9) NULL COMMENT 'Número de contribuinte de cliente (até nove digitos)' ,
  `morada` VARCHAR(250) NULL COMMENT 'Morada de cliente (até 250 caracteres)' ,
  `telefone` INT(9) NULL COMMENT 'Número de telefone de cliente (até 9 digitos)' ,
  `email` VARCHAR(100) NULL COMMENT 'Email de telefone de cliente (até 200 caracteres)' ,
  `notas` VARCHAR(250) NULL COMMENT 'Notas cliente (até 250 caracteres)' ,
  `photo_path` VARCHAR(250) NULL COMMENT 'Caminho da foto do cliente' ,
  `photo_path_aux` VARCHAR(250) NULL COMMENT 'Caminho da foto do cliente auxiliar para código' ,
  PRIMARY KEY (`id`) ,
  INDEX `FK_user_id_idx` (`user_id` ASC) ,
  CONSTRAINT `FK_client_user`
    FOREIGN KEY (`user_id` )
    REFERENCES `gas-inspector-tracker`.`users` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `gas-inspector-tracker`.`inspections`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `gas-inspector-tracker`.`inspections` (
  `id` INT NOT NULL ,
  `user_id` INT(10) NOT NULL COMMENT 'ID de utilizador que criou cliente (até 10 digitos)' ,
  `client_id` INT(10) NOT NULL COMMENT 'ID de cliente (até 10 digitos)' ,
  `data_inspecao` DATE NOT NULL COMMENT 'Data de inspeção (data)' ,
  `data_prox_inspecao` DATE NOT NULL COMMENT 'Data de próxima inspeção (data)' ,
  `descricao` VARCHAR(250) NULL COMMENT 'Descrição de inspeção (250 caracteres)',
  `notas` VARCHAR(250) NULL COMMENT 'Notas inspeção (até 250 caracteres)' ,
  PRIMARY KEY (`id`) ,
  INDEX `FK_user_id_idx` (`user_id` ASC) ,
  INDEX `FK_client_id_idx` (`client_id` ASC) ,
  CONSTRAINT `FK_inspection_user`
    FOREIGN KEY (`user_id` )
    REFERENCES `gas-inspector-tracker`.`users` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `FK_inspection_client`
    FOREIGN KEY (`client_id` )
    REFERENCES `gas-inspector-tracker`.`clients` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

USE `gas-inspector-tracker` ;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;