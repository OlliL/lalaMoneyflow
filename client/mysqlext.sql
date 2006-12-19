DELIMITER $$

DROP FUNCTION IF EXISTS calc_amount$$
CREATE FUNCTION calc_amount (
  pi_amount FLOAT(7,2)
 ,pi_type   VARCHAR(3)
 ,pi_userid INT(10))
  RETURNS FLOAT(7,2)
BEGIN
  DECLARE l_amount FLOAT(7,2);
  DECLARE l_rate FLOAT;

  SELECT rate
    INTO l_rate
    FROM currencies
   WHERE id = (SELECT value
                 FROM settings
                WHERE name = 'displayed_currency'  
                  AND userid = pi_userid)
     AND userid = pi_userid;

  IF pi_type = 'OUT' THEN  
    SET l_amount = ROUND(l_rate*pi_amount,2);
  ELSEIF pi_type = 'IN' THEN
    SET l_amount = ROUND(pi_amount/l_rate,2);
  END IF;

  RETURN l_amount;
END;$$

DELIMITER ;


