CREATE DATABASE  IF NOT EXISTS `db_betting`;
USE `db_betting`;



DROP TABLE IF EXISTS `tb_enderecos`;
DROP TABLE IF EXISTS `tb_senhasrecuperadas`;
DROP TABLE IF EXISTS `tb_vinculos`;
DROP TABLE IF EXISTS `tb_usuarios`;


CREATE TABLE `tb_usuarios` (
	`idusuario` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`desnome` varchar(32) NOT NULL,
	`dessobrenome` varchar(32) NOT NULL,
	`deslogin` varchar(64) NOT NULL,
	`dessenha` varchar(72) NOT NULL,
	`desemail` varchar(64) NOT NULL,
	`inttelefone` bigint(20) DEFAULT NULL,
	`intativo` tinyint(3) UNSIGNED DEFAULT '1',
	`dteultimologin` datetime DEFAULT NULL,
	`dteregistro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  	CONSTRAINT `PK_usuarios` PRIMARY KEY (`idusuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE `tb_vinculos` (
	`idvinculo` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`idusuario` int(11) UNSIGNED NOT NULL,
	`intvinculo` smallint(5) UNSIGNED NOT NULL DEFAULT 1,
	`intprivilegio` smallint(5) UNSIGNED NOT NULL DEFAULT 1,
	`dteadmissao` datetime DEFAULT NULL,
	`dteregistro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 	CONSTRAINT `PK_vinculos` PRIMARY KEY (`idvinculo`),
	CONSTRAINT `FK_vinculos_usuarios` FOREIGN KEY (`idusuario`) 
		REFERENCES `tb_usuarios` (`idusuario`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE `tb_senhasrecuperadas` (
	`idrecuperacao` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`idusuario` int(11) UNSIGNED NOT NULL,
	`desip` varchar(45) NOT NULL,
	`dterecuperacao` datetime DEFAULT NULL,
	`dteregistro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 	CONSTRAINT `PK_senhasrecuperadas` PRIMARY KEY (`idrecuperacao`),
	CONSTRAINT `FK_senhasrecuperadas_usuarios` FOREIGN KEY (`idusuario`) 
		REFERENCES `tb_usuarios` (`idusuario`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE `tb_enderecos` (
	`idendereco` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`idusuario` int(11) UNSIGNED NOT NULL,
	`desendereco` varchar(128) NOT NULL,
	`intnumero` int(10) UNSIGNED NOT NULL,
	`descomplemento` varchar(32) DEFAULT NULL,
	`descidade` varchar(32) NOT NULL,
	`desestado` varchar(32) NOT NULL,
	`despais` varchar(32) NOT NULL,
	`intcep` int(11) NOT NULL,
	`dteregistro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  	CONSTRAINT `PK_enderecos` PRIMARY KEY (`idendereco`),
	CONSTRAINT `FK_enderecos_usuarios` FOREIGN KEY (`idusuario`)
		REFERENCES `tb_usuarios` (`idusuario`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE `tb_country` (
	`idcountry` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`country_id` int(11) UNSIGNED NOT NULL,
	`country_name` varchar(32) NOT NULL,
	`dteregistro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  	CONSTRAINT `PK_country` PRIMARY KEY (`idcountry`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;




































-----------------------------------------------------------------------
-- Tabela de carrinho de aposta
DROP TABLE IF EXISTS `tb_carts`;

CREATE TABLE `tb_carts` (
	`idcart` int(11) NOT NULL,
	`iduser` int(11) DEFAULT NULL,
	`strsessionid` varchar(64) NOT NULL,
	`decfreight` decimal(10,2) DEFAULT NULL,
	`dteatualizado_em`	timestamp DEFAULT NULL,
	`dtecriado_em`	timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  	CONSTRAINT `PK_carts` PRIMARY KEY (`idcart`),
	CONSTRAINT `FK_carts_addresses` FOREIGN KEY (`idaddress`)
		REFERENCES `tb_usuarios` (`iduser`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-----------------------------------------------------------------------
-- Tabela do status das apostas
DROP TABLE IF EXISTS `tb_ordersstatus`;

CREATE TABLE `tb_ordersstatus` (
	`idstatus` int(11) NOT NULL AUTO_INCREMENT,
	`strstatus` varchar(32) NOT NULL,
	`dteatualizado_em`	timestamp DEFAULT NULL,
	`dtecriado_em`	timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	CONSTRAINT `PK_ordersstatus` PRIMARY KEY (`idstatus`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



-----------------------------------------------------------------------
-- Tabela dos pedidos das apostas
DROP TABLE IF EXISTS `tb_orders`;

CREATE TABLE `tb_orders` (
	`idorder` int(11) NOT NULL AUTO_INCREMENT,
	`idcart` int(11) NOT NULL,
	`iduser` int(11) NOT NULL,
	`idstatus` int(11) NOT NULL,
	`dectotal` decimal(10,2) NOT NULL,
	`dteatualizado_em`	timestamp DEFAULT NULL,
	`dtecriado_em`	timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	CONSTRAINT `PK_orders` PRIMARY KEY (`idorder`),
	CONSTRAINT `FK_orders_carts` FOREIGN KEY (`idcart`) 
		REFERENCES `tb_carts` (`idcart`) ON DELETE NO ACTION ON UPDATE NO ACTION,
	CONSTRAINT `FK_orders_ordersstatus` FOREIGN KEY (`idstatus`) 
		REFERENCES `tb_ordersstatus` (`idstatus`) ON DELETE NO ACTION ON UPDATE NO ACTION,
	CONSTRAINT `FK_orders_users` FOREIGN KEY (`iduser`) 
		REFERENCES `tb_usuarios` (`iduser`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-----------------------------------------------------------------------
-- Tabela dos recuperação da senha do usuário
DROP TABLE IF EXISTS `tb_senhasrecuperadas`;

CREATE TABLE `tb_senhasrecuperadas` (
	`idrecovery` int(11) NOT NULL AUTO_INCREMENT,
	`iduser` int(11) NOT NULL,
	`strip` varchar(45) NOT NULL,
	`dteatualizado_em`	timestamp DEFAULT NULL,
	`dtecriado_em`	timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	CONSTRAINT `PK_userspasswordsrecoveries` PRIMARY KEY (`idrecovery`),
	CONSTRAINT `FK_userspasswordsrecoveries_users` FOREIGN KEY (`iduser`) 
		REFERENCES `tb_usuarios` (`iduser`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-----------------------------------------------------------------------
-- Tabela dos paises
DROP TABLE IF EXISTS `tb_countries`;

CREATE TABLE `tb_countries` (
	`idcountry` int(11) NOT NULL AUTO_INCREMENT,
	`intcountry_id` smallint(5) NOT NULL,
	`strcountry_name` varchar(64) NOT NULL,
	`dteatualizado_em`	timestamp DEFAULT NULL,
	`dtecriado_em`	timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	CONSTRAINT `PK_countries` PRIMARY KEY (`idcountry`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-----------------------------------------------------------------------
-- Tabela das competições
DROP TABLE IF EXISTS `tb_competitions`;

CREATE TABLE `tb_competitions` (
	`idcompetition` int(11) NOT NULL AUTO_INCREMENT,
	`idcountry` int(11) NOT NULL,
	`intleague_id` smallint(5) NOT NULL,
	`strleague_name` varchar(64) NOT NULL,
	`dteatualizado_em`	timestamp DEFAULT NULL,
	`dtecriado_em`	timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	CONSTRAINT `PK_competitions` PRIMARY KEY (`idrecovery`),
	CONSTRAINT `FK_competitions_countries` FOREIGN KEY (`idcountry`) 
		REFERENCES `tb_countries` (`idcountry`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-----------------------------------------------------------------------
-- Tabela das competições