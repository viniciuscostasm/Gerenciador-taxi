DELIMITER $$
-- Procedure de Insert --
CREATE PROCEDURE sp_tax_request_ins(IN p_usr_int_id INT(11), IN p_req_cha_origin CHAR(1), IN p_req_dat_date DATE, IN p_req_var_hour VARCHAR(5), IN p_emp_int_id INT(11), IN p_req_var_passenger VARCHAR(100), IN p_req_var_address VARCHAR(255), IN p_cit_int_id INT(11), IN p_dis_int_id INT(11), IN p_zon_int_id INT(11), IN p_mot_int_id INT(11), IN p_req_txt_comment TEXT,INOUT p_status BOOLEAN, INOUT p_msg TEXT, INOUT p_insert_id INT(11))
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

    INSERT INTO tax_request(
		usr_int_id,
		req_cha_origin,
		req_dat_date,
		req_var_hour,
		emp_int_id,
		req_var_passenger,
		req_var_address,
		cit_int_id,
		dis_int_id,
		zon_int_id,
		mot_int_id,
		req_txt_comment
    ) VALUES (
		p_usr_int_id,
		p_req_cha_origin,
		p_req_dat_date,
		p_req_var_hour,
		p_emp_int_id,
		p_req_var_passenger,
		p_req_var_address,
		p_cit_int_id,
		p_dis_int_id,
		p_zon_int_id,
		p_mot_int_id,
		p_req_txt_comment
    );

    COMMIT;

    SET p_status = TRUE;
    SET p_msg = 'A new record has been successfully inserted.';
    SET p_insert_id = LAST_INSERT_ID();

  END IF;

END$$



DELIMITER $$
-- Procedure de Update --
CREATE PROCEDURE sp_tax_request_upd(IN p_req_int_id INT(11), IN p_usr_int_id INT(11), IN p_req_cha_origin CHAR(1), IN p_req_dat_date DATE, IN p_req_var_hour VARCHAR(5), IN p_emp_int_id INT(11), IN p_req_var_passenger VARCHAR(100), IN p_req_var_address VARCHAR(255), IN p_cit_int_id INT(11), IN p_dis_int_id INT(11), IN p_zon_int_id INT(11), IN p_mot_int_id INT(11), IN p_req_txt_comment TEXT,INOUT p_status BOOLEAN, INOUT p_msg TEXT)
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
  FROM tax_request
  WHERE req_int_id = p_req_int_id;

  IF NOT v_existe THEN
    SET p_msg = concat(p_msg, 'Record not found.<br />');
  END IF;

  IF p_msg = '' THEN
    START TRANSACTION;

    UPDATE tax_request
    SET 
		usr_int_id = p_usr_int_id,
		req_cha_origin = p_req_cha_origin,
		req_dat_date = p_req_dat_date,
		req_var_hour = p_req_var_hour,
		emp_int_id = p_emp_int_id,
		req_var_passenger = p_req_var_passenger,
		req_var_address = p_req_var_address,
		cit_int_id = p_cit_int_id,
		dis_int_id = p_dis_int_id,
		zon_int_id = p_zon_int_id,
		mot_int_id = p_mot_int_id,
		req_txt_comment = p_req_txt_comment
    WHERE req_int_id = p_req_int_id;

    COMMIT;

    SET p_status = TRUE;
    SET p_msg = 'The record was successfully changed';

  END IF;

END$$



DELIMITER $$
-- Procedure de Delete --
CREATE PROCEDURE sp_tax_request_del(IN p_req_int_id INT(11),INOUT p_status BOOLEAN, INOUT p_msg TEXT)
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
  FROM tax_request
  WHERE req_int_id = p_req_int_id;

  IF NOT v_existe THEN
    SET p_msg = concat(p_msg, 'Record not found.<br />');
  END IF;

  CALL sp_adm_dependencia('tax_request', p_req_int_id, @dependencias);
  SET p_msg = concat(p_msg,IF(@dependencias IS NULL, '', @dependencias));

  IF p_msg = '' THEN
    START TRANSACTION;

    DELETE FROM tax_request
    WHERE req_int_id = p_req_int_id;

    SELECT ROW_COUNT() INTO v_row_count;

    COMMIT;

    IF (v_row_count > 0) THEN
      SET p_status = TRUE;
      SET p_msg = 'The record was successfully deleted';
    END IF;

  END IF;

END$$
DELIMITER ;