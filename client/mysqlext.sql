DELIMITER $$

DROP FUNCTION IF EXISTS calc_amount$$
CREATE FUNCTION calc_amount (
  pi_amount FLOAT(7,2)
 ,pi_type   VARCHAR(3))
  RETURNS FLOAT(7,2)
BEGIN
  DECLARE l_amount FLOAT(7,2);

  IF pi_type = 'OUT' THEN  
    SELECT ROUND(rate*pi_amount,2)
      INTO l_amount
      FROM currencies
     WHERE id = (SELECT value
                   FROM settings
                  WHERE name = 'displayed_currency');
  ELSEIF pi_type = 'IN' THEN
    SELECT ROUND(pi_amount/rate,2)
      INTO l_amount
      FROM currencies
     WHERE id = (SELECT value
                   FROM settings
                  WHERE name = 'displayed_currency');
  END IF;

  RETURN l_amount;
END;$$

DELIMITER ;


