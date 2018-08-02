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



CREATE TABLE `tb_mycountry` (
	`idmycountry` 	int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`descountry` 	varchar(40) NOT NULL,
	`intactive` 	tinyint(3) UNSIGNED DEFAULT '1',
	`intbetting` 	int(10) UNSIGNED DEFAULT '0',
	`intclick` 	int(10) UNSIGNED DEFAULT '0',
	`dteregistro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  	CONSTRAINT `PK_mycountry` PRIMARY KEY (`idmycountry`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE `tb_myleague` (
	`idmyleague` 	int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`idmycountry` 	int(11) UNSIGNED NOT NULL,

	`ideventapi` 	int(11) UNSIGNED NOT NULL,
	`desleague` 	varchar(50) NOT NULL,
	`intactive` 	tinyint(3) UNSIGNED DEFAULT '1',
	`dteregistro` 	timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  	CONSTRAINT `PK_myleague` PRIMARY KEY (`idmyleague`),
  	CONSTRAINT `FK_myleague_mycountry` FOREIGN KEY (`idmycountry`) 
		REFERENCES `tb_mycountry` (`idmycountry`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE `tb_myevent` (
	`idmyevent` 		int(11) UNSIGNED NOT NULL,
	`idmyleague` 		int(11) UNSIGNED NOT NULL,

	`idevent` 			int(11) UNSIGNED NOT NULL,
	`idhometeam`		int(11) UNSIGNED NOT NULL,
	`idawayteam`		int(11) UNSIGNED NOT NULL,
	`deshometeam`		varchar(50) NOT NULL,
	`desawayteam`		varchar(50) NOT NULL,
	`dtetime`			datetime NOT NULL,
	`inttimestatus`		tinyint(3) DEFAULT '0',
	`intactive`			tinyint(3) UNSIGNED DEFAULT '1',
	`dteupdate` 		timestamp DEFAULT NOW(),
	`dteregistro` 		timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  	CONSTRAINT `PK_myevent` PRIMARY KEY (`idmyevent`),
  	CONSTRAINT `FK_myevent_myleague` FOREIGN KEY (`idmyleague`) 
		REFERENCES `tb_myleague` (`idmyleague`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE `tb_mytypeodd` (
	`idmytypeodd` 	int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

	`idodd` 		smallint(5) UNSIGNED NOT NULL,
	`desodd`		varchar(40) DEFAULT NULL,
	`intactive`		tinyint(3) UNSIGNED DEFAULT '0',
	`dteregistro` 	timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  	CONSTRAINT `PK_mytypeodd` PRIMARY KEY (`idmytypeodd`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE `tb_myodd` (
	`idmyodd` 		int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`idmytypeodd`	int(11) UNSIGNED NOT NULL,
	`idmyevent`		int(11) UNSIGNED NOT NULL,

	`idodd` 		smallint(5) UNSIGNED NOT NULL,
	`idevent` 		int(11) UNSIGNED NOT NULL,

	`desopp`		varchar(64) NOT NULL,
	`desodds`		varchar(10) NOT NULL,
	`desheader`		varchar(10) NOT NULL,

	`dteregistro` 	timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  	CONSTRAINT `PK_myodd` PRIMARY KEY (`idmyodd`),
  	CONSTRAINT `FK_myodd_myevent` FOREIGN KEY (`idmyevent`) 
		REFERENCES `tb_myevent` (`idmyevent`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  	CONSTRAINT `FK_myodd_mytypeodd` FOREIGN KEY (`idmytypeodd`) 
		REFERENCES `tb_mytypeodd` (`idmytypeodd`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE `tb_time` (
	`idtime` 		tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT,

	`destype` 		varchar(10) NOT NULL,
	`destime`		varchar(64) NOT NULL,
	`dteupdate` 	timestamp NOT NULL,

	`dteregistro` 	timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  	CONSTRAINT `PK_time` PRIMARY KEY (`idtime`)
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