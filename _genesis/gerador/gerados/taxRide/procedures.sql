DELIMITER $$
-- Procedure de Insert --
CREATE PROCEDURE sp_tax_ride_ins(IN p_tco_cha_type CHAR(3), IN p_rid_dat_date DATE, IN p_rid_hou_hour VARCHAR(5), IN p_txc_int_id INT(11), IN p_rid_cha_status CHAR(3), IN p_rid_int_passengers INT(2), IN p_zon_int_id INT(11), IN p_zon_int_idlist TEXT, IN p_zon_var_namelist TEXT, IN p_rid_txt_passengerlist TEXT, IN p_rid_hor_stopped CHAR(5), IN p_rid_dec_stoppedhour DECIMAL(10,2), IN p_rid_dec_parking DECIMAL(10,2), IN p_rid_dec_transfer DECIMAL(10,2), IN p_rid_dec_value DECIMAL(10,2), IN p_usr_int_idadd INT(11), IN p_usr_int_idarrival INT(11), IN p_rid_var_plate VARCHAR(8), IN p_rid_txt_comment TEXT, IN p_rid_var_driver VARCHAR(100), IN p_rid_hou_arrival VARCHAR(4),INOUT p_status BOOLEAN, INOUT p_msg TEXT, INOUT p_insert_id INT(11))
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

    INSERT INTO tax_ride(
		tco_cha_type,
		rid_dat_date,
		rid_hou_hour,
		txc_int_id,
		rid_cha_status,
		rid_int_passengers,
		zon_int_id,
		zon_int_idlist,
		zon_var_namelist,
		rid_txt_passengerlist,
		rid_hor_stopped,
		rid_dec_stoppedhour,
		rid_dec_parking,
		rid_dec_transfer,
		rid_dec_value,
		usr_int_idadd,
		usr_int_idarrival,
		rid_var_plate,
		rid_txt_comment,
		rid_var_driver,
		rid_hou_arrival
    ) VALUES (
		p_tco_cha_type,
		p_rid_dat_date,
		p_rid_hou_hour,
		p_txc_int_id,
		p_rid_cha_status,
		p_rid_int_passengers,
		p_zon_int_id,
		p_zon_int_idlist,
		p_zon_var_namelist,
		p_rid_txt_passengerlist,
		p_rid_hor_stopped,
		p_rid_dec_stoppedhour,
		p_rid_dec_parking,
		p_rid_dec_transfer,
		p_rid_dec_value,
		p_usr_int_idadd,
		p_usr_int_idarrival,
		p_rid_var_plate,
		p_rid_txt_comment,
		p_rid_var_driver,
		p_rid_hou_arrival
    );

    COMMIT;

    SET p_status = TRUE;
    SET p_msg = 'A new record has been successfully inserted.';
    SET p_insert_id = LAST_INSERT_ID();

  END IF;

END$$



DELIMITER $$
-- Procedure de Update --
CREATE PROCEDURE sp_tax_ride_upd(IN p_rid_int_id INT(11), IN p_tco_cha_type CHAR(3), IN p_rid_dat_date DATE, IN p_rid_hou_hour VARCHAR(5), IN p_txc_int_id INT(11), IN p_rid_cha_status CHAR(3), IN p_rid_int_passengers INT(2), IN p_zon_int_id INT(11), IN p_zon_int_idlist TEXT, IN p_zon_var_namelist TEXT, IN p_rid_txt_passengerlist TEXT, IN p_rid_hor_stopped CHAR(5), IN p_rid_dec_stoppedhour DECIMAL(10,2), IN p_rid_dec_parking DECIMAL(10,2), IN p_rid_dec_transfer DECIMAL(10,2), IN p_rid_dec_value DECIMAL(10,2), IN p_usr_int_idadd INT(11), IN p_usr_int_idarrival INT(11), IN p_rid_var_plate VARCHAR(8), IN p_rid_txt_comment TEXT, IN p_rid_var_driver VARCHAR(100), IN p_rid_hou_arrival VARCHAR(4),INOUT p_status BOOLEAN, INOUT p_msg TEXT)
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
  FROM tax_ride
  WHERE rid_int_id = p_rid_int_id;

  IF NOT v_existe THEN
    SET p_msg = concat(p_msg, 'Record not found.<br />');
  END IF;

  IF p_msg = '' THEN
    START TRANSACTION;

    UPDATE tax_ride
    SET 
		tco_cha_type = p_tco_cha_type,
		rid_dat_date = p_rid_dat_date,
		rid_hou_hour = p_rid_hou_hour,
		txc_int_id = p_txc_int_id,
		rid_cha_status = p_rid_cha_status,
		rid_int_passengers = p_rid_int_passengers,
		zon_int_id = p_zon_int_id,
		zon_int_idlist = p_zon_int_idlist,
		zon_var_namelist = p_zon_var_namelist,
		rid_txt_passengerlist = p_rid_txt_passengerlist,
		rid_hor_stopped = p_rid_hor_stopped,
		rid_dec_stoppedhour = p_rid_dec_stoppedhour,
		rid_dec_parking = p_rid_dec_parking,
		rid_dec_transfer = p_rid_dec_transfer,
		rid_dec_value = p_rid_dec_value,
		usr_int_idadd = p_usr_int_idadd,
		usr_int_idarrival = p_usr_int_idarrival,
		rid_var_plate = p_rid_var_plate,
		rid_txt_comment = p_rid_txt_comment,
		rid_var_driver = p_rid_var_driver,
		rid_hou_arrival = p_rid_hou_arrival
    WHERE rid_int_id = p_rid_int_id;

    COMMIT;

    SET p_status = TRUE;
    SET p_msg = 'The record was successfully changed';

  END IF;

END$$



DELIMITER $$
-- Procedure de Delete --
CREATE PROCEDURE sp_tax_ride_del(IN p_rid_int_id INT(11),INOUT p_status BOOLEAN, INOUT p_msg TEXT)
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
  FROM tax_ride
  WHERE rid_int_id = p_rid_int_id;

  IF NOT v_existe THEN
    SET p_msg = concat(p_msg, 'Record not found.<br />');
  END IF;

  CALL sp_adm_dependence('tax_ride', p_rid_int_id, @dependencias);
  SET p_msg = concat(p_msg,IF(@dependencias IS NULL, '', @dependencias));

  IF p_msg = '' THEN
    START TRANSACTION;

    DELETE FROM tax_ride
    WHERE rid_int_id = p_rid_int_id;

    SELECT ROW_COUNT() INTO v_row_count;

    COMMIT;

    IF (v_row_count > 0) THEN
      SET p_status = TRUE;
      SET p_msg = 'The record was successfully deleted';
    END IF;

  END IF;

END$$
DELIMITER ;