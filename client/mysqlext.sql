CREATE OR REPLACE SQL SECURITY INVOKER VIEW vw_text (
   id
  ,text
  ,type
  ,userid)
  AS SELECT mtx.id
           ,mtx.text
           ,mtx.type
           ,mse.userid
      FROM text     mtx
          ,settings mse
     WHERE mtx.languageid = mse.value
       AND mse.name       = 'displayed_language';

CREATE OR REPLACE SQL SECURITY INVOKER VIEW vw_template_text (
   userid
  ,name
  ,variable
  ,text)
  AS SELECT mvt.userid
           ,mte.name
           ,CONCAT('TEXT_',mvt.id)
           ,mvt.text
      FROM templates mte
          ,vw_text   mvt
     WHERE mte.textid = mvt.id
       AND mvt.type   = 't';


DELIMITER $$

DROP FUNCTION IF EXISTS calc_amount$$
CREATE FUNCTION calc_amount (
  pi_amount FLOAT(8,2)
 ,pi_type   VARCHAR(3)
 ,pi_userid INT(10) UNSIGNED)
  RETURNS FLOAT(8,2)
BEGIN
  DECLARE l_amount FLOAT(7,2);
  DECLARE l_rate FLOAT;

  SELECT rate
    INTO l_rate
    FROM currencies
   WHERE id = (SELECT value
                 FROM settings
                WHERE name = 'displayed_currency'  
                  AND userid = pi_userid);

  IF pi_type = 'OUT' THEN  
    SET l_amount = ROUND(l_rate*pi_amount,2);
  ELSEIF pi_type = 'IN' THEN
    SET l_amount = ROUND(pi_amount/l_rate,2);
  END IF;

  RETURN l_amount;
END;$$

DELIMITER ;


