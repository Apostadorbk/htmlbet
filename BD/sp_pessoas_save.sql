DELIMITER $$

CREATE PROCEDURE `sp_pessoas_save`(
    pdesnome        varchar(32),
    pdessobrenome   varchar(32),
    pdesemail       varchar(64),
    pdeslogin       varchar(64),
    pdessenha       varchar(72),
    OUT psuccess    BOOLEAN
)
BEGIN
	
    DECLARE vidpessoa INT;
    DECLARE vidvinculo INT;

    START TRANSACTION;

    SET psuccess = TRUE;

    IF NOT EXISTS(SELECT idpessoa FROM tb_pessoas WHERE deslogin = pdeslogin) THEN
        INSERT INTO tb_pessoas VALUES(
            NULL, 
            pdesnome, 
            pdessobrenome, 
            pdeslogin, 
            pdessenha, 
            pdesemail, 
            NULL, 
            1, 
            NULL, 
            NULL, 
            NULL
        );

        SET vidpessoa = LAST_INSERT_ID();

    ELSE

        SET psuccess = FALSE;

        SELECT "Usuário já cadastrado!" AS msg;

        ROLLBACK;

    END IF;


    IF NOT EXISTS(SELECT idpessoa FROM tb_vinculos WHERE idpessoa = vidpessoa) THEN
        INSERT INTO tb_vinculos VALUES(
            NULL, 
            vidpessoa,
            1,
            1,
            NULL, 
            NULL
        );

        SET vidvinculo = LAST_INSERT_ID();

    ELSE

        SET psuccess = FALSE;

        SELECT "Vinculo do usuário já cadastrado!" AS msg;

        ROLLBACK;

    END IF;

    COMMIT;

    SELECT "Dados cadastrado com sucesso!" as msg;

    
END $$

DELIMITER ;

