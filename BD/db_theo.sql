DROP DATABASE IF EXISTS `db_theo`;

CREATE DATABASE  IF NOT EXISTS `db_theo`;

USE `db_theo`;


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
	`desfoto` varchar(256) DEFAULT NULL,
	`dteultimologin` timestamp DEFAULT NULL,
	`dteregistro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  	CONSTRAINT `PK_usuario` PRIMARY KEY (`idusuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE `tb_vinculos` (
	`idvinculo` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`idusuario` int(11) UNSIGNED NOT NULL,
	`intvinculo` smallint(5) UNSIGNED NOT NULL DEFAULT 1,
	`intprivilegio` smallint(5) UNSIGNED NOT NULL DEFAULT 1,
	`dteadmissao` timestamp DEFAULT NULL,
	`dteregistro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 	CONSTRAINT `PK_vinculos` PRIMARY KEY (`idvinculo`),
	CONSTRAINT `FK_vinculos_usuario` FOREIGN KEY (`idusuario`) 
		REFERENCES `tb_usuarios` (`idusuario`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE `tb_senhasrecuperadas` (
	`idrecuperacao` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`idusuario` int(11) UNSIGNED NOT NULL,
	`desip` varchar(45) NOT NULL,
	`dterecuperacao` timestamp DEFAULT NULL,
	`dteregistro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 	CONSTRAINT `PK_senhasrecuperadas` PRIMARY KEY (`idrecuperacao`),
	CONSTRAINT `FK_senhasrecuperadas_usuario` FOREIGN KEY (`idusuario`) 
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
	CONSTRAINT `FK_enderecos_usuario` FOREIGN KEY (`idusuario`)
		REFERENCES `tb_usuarios` (`idusuario`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

