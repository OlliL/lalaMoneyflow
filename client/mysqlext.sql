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

DELIMITER ;


