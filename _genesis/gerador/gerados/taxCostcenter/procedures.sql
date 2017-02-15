DELIMITER $$
-- Procedure de Insert --
CREATE PROCEDURE sp_tax_costcenter_ins(IN p_coc_var_key VARCHAR(10), IN p_coc_var_name VARCHAR(50),INOUT p_status BOOLEAN, INOUT p_msg TEXT, INOUT p_insert_id INT(11))
    NOT DETERMINISTIC
    CONTAINS SQL
    SQL SECURITY INVOKER
    COMMENT 'Procedure de Insert'
BEGIN

  DECLARE EXIT HANDLER FOR SQLEXCEPTION
  BEGIN
    ROLLBACK;
    SET p_status = FALSE;
    SET p_msg = 'Error while performing the procedure.';
  END;

  SET p_msg = '';
  SET p_status = FALSE;

  -- VALIDATIONS
  -- IF condicao THEN
  --    SET p_msg = concat(p_msg, 'Mensagem.<br />');
  -- END IF;

  IF p_msg = '' THEN

    START TRANSACTION;

    INSERT INTO tax_costcenter(
		coc_var_key,
		coc_var_name
    ) VALUES (
		p_coc_var_key,
		p_coc_var_name
    );

    COMMIT;

    SET p_status = TRUE;
    SET p_msg = 'A new record has been successfully inserted.';
    SET p_insert_id = LAST_INSERT_ID();

  END IF;

END$$



DELIMITER $$
-- Procedure de Update --
CREATE PROCEDURE sp_tax_costcenter_upd(IN p_coc_int_id INT(11), IN p_coc_var_key VARCHAR(10), IN p_coc_var_name VARCHAR(50),INOUT p_status BOOLEAN, INOUT p_msg TEXT)
    NOT DETERMINISTIC
    CONTAINS SQL
    SQL SECURITY INVOKER
    COMMENT 'Procedure de Update'
BEGIN

  DECLARE v_existe BOOLEAN;

  DECLARE EXIT HANDLER FOR SQLEXCEPTION
  BEGIN
    ROLLBACK;
    SET p_status = FALSE;
    SET p_msg = 'Error while performing the procedure.';
  END;

  SET p_msg = '';
  SET p_status = FALSE;

  -- VALIDATIONS
  SELECT IF(count(1) = 0, FALSE, TRUE)
  INTO v_existe
  FROM tax_costcenter
  WHERE coc_int_id = p_coc_int_id;

  IF NOT v_existe THEN
    SET p_msg = concat(p_msg, 'Record not found.<br />');
  END IF;

  IF p_msg = '' THEN
    START TRANSACTION;

    UPDATE tax_costcenter
    SET 
		coc_var_key = p_coc_var_key,
		coc_var_name = p_coc_var_name
    WHERE coc_int_id = p_coc_int_id;

    COMMIT;

    SET p_status = TRUE;
    SET p_msg = 'The record was successfully changed';

  END IF;

END$$



DELIMITER $$
-- Procedure de Delete --
CREATE PROCEDURE sp_tax_costcenter_del(IN p_coc_int_id INT(11),INOUT p_status BOOLEAN, INOUT p_msg TEXT)
    NOT DETERMINISTIC
    CONTAINS SQL
    SQL SECURITY INVOKER
    COMMENT 'Procedure de Delete'
BEGIN

  DECLARE v_existe BOOLEAN;
  DECLARE v_row_count int DEFAULT 0;

  DECLARE EXIT HANDLER FOR SQLEXCEPTION
  BEGIN
    ROLLBACK;
    SET p_status = FALSE;
    SET p_msg = 'Error while performing the procedure.';
  END;

  SET p_msg = '';
  SET p_status = FALSE;

  -- VALIDATIONS
  SELECT IF(count(1) = 0, FALSE, TRUE)
  INTO v_existe
  FROM tax_costcenter
  WHERE coc_int_id = p_coc_int_id;

  IF NOT v_existe THEN
    SET p_msg = concat(p_msg, 'Record not found.<br />');
  END IF;

  CALL sp_adm_dependence('tax_costcenter', p_coc_int_id, @dependencias);
  SET p_msg = concat(p_msg,IF(@dependencias IS NULL, '', @dependencias));

  IF p_msg = '' THEN
    START TRANSACTION;

    DELETE FROM tax_costcenter
    WHERE coc_int_id = p_coc_int_id;

    SELECT ROW_COUNT() INTO v_row_count;

    COMMIT;

    IF (v_row_count > 0) THEN
      SET p_status = TRUE;
      SET p_msg = 'The record was successfully deleted';
    END IF;

  END IF;

END$$
DELIMITER ;