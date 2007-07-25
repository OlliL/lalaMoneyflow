CREATE OR REPLACE SQL SECURITY INVOKER VIEW vw_text (
   textid
  ,text
  ,type
  ,mur_userid)
  AS SELECT mtx.textid
           ,mtx.text
           ,mtx.type
           ,mse.mur_userid
      FROM text     mtx
          ,settings mse
     WHERE mtx.mla_languageid = mse.value
       AND mse.name           = 'displayed_language';

CREATE OR REPLACE SQL SECURITY INVOKER VIEW vw_template_text (
   mur_userid
  ,name
  ,variable
  ,text)
  AS SELECT mvt.mur_userid
           ,mte.name
           ,CONCAT('TEXT_',mvt.textid)
           ,mvt.text
      FROM templates mte
          ,vw_text   mvt
     WHERE mte.mtx_textid = mvt.textid
       AND mvt.type       = 't';


DELIMITER $$

DROP FUNCTION IF EXISTS calc_amount$$
CREATE FUNCTION calc_amount (
  pi_amount FLOAT(8,2)
 ,pi_type   VARCHAR(3)
 ,pi_userid INT(10) UNSIGNED
 ,pi_date   DATE)
  RETURNS FLOAT(8,2)
BEGIN
  DECLARE l_amount FLOAT(7,2);
  DECLARE l_rate FLOAT;

  SELECT rate
    INTO l_rate
    FROM currencyrates
   WHERE mcu_currencyid = (SELECT value
                             FROM settings
                            WHERE name = 'displayed_currency'  
                              AND mur_userid = pi_userid)
     AND pi_date BETWEEN validfrom AND validtil;

  IF pi_type = 'OUT' THEN  
    SET l_amount = ROUND(l_rate*pi_amount,2);
  ELSEIF pi_type = 'IN' THEN
    SET l_amount = ROUND(pi_amount/l_rate,2);
  END IF;

  RETURN l_amount;
END;$$

DROP TRIGGER IF EXISTS mpm_trg_01$$
CREATE TRIGGER mpm_trg_01 BEFORE INSERT ON predefmoneyflows
  FOR EACH ROW BEGIN
    SET NEW.createdate = NOW();
  END;
$$

DROP FUNCTION IF EXISTS user_owns_data$$
CREATE FUNCTION user_owns_data (
  pi_userid INT(10) UNSIGNED)
  RETURNS INT(1)
BEGIN
  DECLARE l_num INT(1);
  
  DECLARE c_mcs CURSOR FOR
    SELECT 1
      FROM capitalsources
     WHERE mur_userid = pi_userid
     LIMIT 1;

  DECLARE c_mcp CURSOR FOR
    SELECT 1
      FROM contractpartners
     WHERE mur_userid = pi_userid
     LIMIT 1;

  DECLARE c_mmf CURSOR FOR
    SELECT 1
      FROM moneyflows
     WHERE mur_userid = pi_userid
     LIMIT 1;

  DECLARE c_mms CURSOR FOR
    SELECT 1
      FROM monthlysettlements
     WHERE mur_userid = pi_userid
     LIMIT 1;

  DECLARE c_mpm CURSOR FOR
    SELECT 1
      FROM predefmoneyflows
     WHERE mur_userid = pi_userid
     LIMIT 1;

  DECLARE CONTINUE HANDLER FOR NOT FOUND SET l_num = 0;

  OPEN  c_mcs;
  FETCH c_mcs INTO l_num;
  CLOSE c_mcs;
  
  IF l_num = 0 THEN

    OPEN  c_mcp;
    FETCH c_mcp INTO l_num;
    CLOSE c_mcp;

    IF l_num = 0 THEN

      OPEN  c_mmf;
      FETCH c_mmf INTO l_num;
      CLOSE c_mmf;

      IF l_num = 0 THEN

        OPEN  c_mms;
        FETCH c_mms INTO l_num;
        CLOSE c_mms;

        IF l_num = 0 THEN

          OPEN  c_mpm;
          FETCH c_mpm INTO l_num;
          CLOSE c_mpm;

        END IF;

      END IF;

    END IF;

  END IF;

  RETURN l_num;
  
END;$$

DROP PROCEDURE IF EXISTS user_delete_data$$
CREATE PROCEDURE user_delete_data (
  pi_userid INT(10) UNSIGNED)
BEGIN
  DECLARE l_num INT(1);
  
  DELETE FROM capitalsources
   WHERE mur_userid = pi_userid;

  DELETE FROM contractpartners
   WHERE mur_userid = pi_userid;

  DELETE FROM moneyflows
   WHERE mur_userid = pi_userid;

  DELETE FROM monthlysettlements
   WHERE mur_userid = pi_userid;

  DELETE FROM predefmoneyflows
   WHERE mur_userid = pi_userid;

  COMMIT;
END;$$

DELIMITER ;


