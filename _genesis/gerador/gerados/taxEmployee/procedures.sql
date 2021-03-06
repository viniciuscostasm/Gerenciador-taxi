DELIMITER $$
-- Procedure de Insert --
CREATE PROCEDURE sp_tax_employee_ins(IN p_coc_int_id INT(11), IN p_emp_var_key VARCHAR(20), IN p_emp_var_name VARCHAR(100), IN p_emp_var_address VARCHAR(255), IN p_emp_var_cep VARCHAR(10), IN p_cit_int_id INT(11), IN p_dis_int_id INT(11),INOUT p_status BOOLEAN, INOUT p_msg TEXT, INOUT p_insert_id INT(11))
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

    INSERT INTO tax_employee(
		coc_int_id,
		emp_var_key,
		emp_var_name,
		emp_var_address,
		emp_var_cep,
		cit_int_id,
		dis_int_id
    ) VALUES (
		p_coc_int_id,
		p_emp_var_key,
		p_emp_var_name,
		p_emp_var_address,
		p_emp_var_cep,
		p_cit_int_id,
		p_dis_int_id
    );

    COMMIT;

    SET p_status = TRUE;
    SET p_msg = 'A new record has been successfully inserted.';
    SET p_insert_id = LAST_INSERT_ID();

  END IF;

END$$



DELIMITER $$
-- Procedure de Update --
CREATE PROCEDURE sp_tax_employee_upd(IN p_emp_int_id INT(11), IN p_coc_int_id INT(11), IN p_emp_var_key VARCHAR(20), IN p_emp_var_name VARCHAR(100), IN p_emp_var_address VARCHAR(255), IN p_emp_var_cep VARCHAR(10), IN p_cit_int_id INT(11), IN p_dis_int_id INT(11),INOUT p_status BOOLEAN, INOUT p_msg TEXT)
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
  FROM tax_employee
  WHERE emp_int_id = p_emp_int_id;

  IF NOT v_existe THEN
    SET p_msg = concat(p_msg, 'Record not found.<br />');
  END IF;

  IF p_msg = '' THEN
    START TRANSACTION;

    UPDATE tax_employee
    SET 
		coc_int_id = p_coc_int_id,
		emp_var_key = p_emp_var_key,
		emp_var_name = p_emp_var_name,
		emp_var_address = p_emp_var_address,
		emp_var_cep = p_emp_var_cep,
		cit_int_id = p_cit_int_id,
		dis_int_id = p_dis_int_id
    WHERE emp_int_id = p_emp_int_id;

    COMMIT;

    SET p_status = TRUE;
    SET p_msg = 'The record was successfully changed';

  END IF;

END$$



DELIMITER $$
-- Procedure de Delete --
CREATE PROCEDURE sp_tax_employee_del(IN p_emp_int_id INT(11),INOUT p_status BOOLEAN, INOUT p_msg TEXT)
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
  FROM tax_employee
  WHERE emp_int_id = p_emp_int_id;

  IF NOT v_existe THEN
    SET p_msg = concat(p_msg, 'Record not found.<br />');
  END IF;

  CALL sp_adm_dependence('tax_employee', p_emp_int_id, @dependencias);
  SET p_msg = concat(p_msg,IF(@dependencias IS NULL, '', @dependencias));

  IF p_msg = '' THEN
    START TRANSACTION;

    DELETE FROM tax_employee
    WHERE emp_int_id = p_emp_int_id;

    SELECT ROW_COUNT() INTO v_row_count;

    COMMIT;

    IF (v_row_count > 0) THEN
      SET p_status = TRUE;
      SET p_msg = 'The record was successfully deleted';
    END IF;

  END IF;

END$$
DELIMITER ;