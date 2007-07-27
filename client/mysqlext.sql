CREATE OR REPLACE SQL SECURITY INVOKER VIEW vw_text (
   textid
  ,text
  ,type
  ,mur_userid
  ) AS
     SELECT mtx.textid
           ,mtx.text
           ,mtx.type
           ,mse.mur_userid
      FROM text     mtx
          ,settings mse
     WHERE mtx.mla_languageid = mse.value
       AND mse.name           = 'displayed_language';

CREATE OR REPLACE SQL SECURITY INVOKER VIEW vw_template_text (
   mur_userid
  ,templatename
  ,variable
  ,text
  ) AS
     SELECT mvt.mur_userid
           ,mtv.mte_templatename
           ,CONCAT('TEXT_',mvt.textid)
           ,mvt.text
      FROM templatevalues mtv
          ,vw_text        mvt
     WHERE mtv.mtx_textid = mvt.textid
       AND mvt.type       = 't';


DELIMITER $$

-- FUNCTIONS

DROP FUNCTION IF EXISTS domain_meaning$$
CREATE FUNCTION domain_meaning (pi_domain VARCHAR(30)
                               ,pi_value  VARCHAR(3)
                               ,pi_userid INT(10) UNSIGNED
                               ) RETURNS  VARCHAR(255)
BEGIN
  DECLARE l_text VARCHAR(255);

  SELECT mtx.text
    INTO l_text
    FROM text         mtx
        ,domainvalues mdv
        ,settings     mse
   WHERE mdv.mdm_domain     = pi_domain
     AND mdv.value          = pi_value
     AND mtx.textid         = mdv.mtx_textid
     AND mtx.mla_languageid = mse.value
     AND mse.name           = 'displayed_language'
     AND mse.mur_userid     = pi_userid;

  RETURN l_text;
END;$$

DROP FUNCTION IF EXISTS calc_amount$$
CREATE FUNCTION calc_amount (pi_amount FLOAT(8,2)
                            ,pi_type   VARCHAR(3)
                            ,pi_userid INT(10) UNSIGNED
                            ,pi_date   DATE
                            ) RETURNS  FLOAT(8,2)
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
    SET l_amount := ROUND(l_rate*pi_amount,2);
  ELSEIF pi_type = 'IN' THEN
    SET l_amount := ROUND(pi_amount/l_rate,2);
  END IF;

  RETURN l_amount;
END;$$

DROP FUNCTION IF EXISTS user_owns_data$$
CREATE FUNCTION user_owns_data (pi_userid INT(10) UNSIGNED
                               ) RETURNS  INT(1)
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

  DECLARE CONTINUE HANDLER FOR NOT FOUND SET l_num := 0;

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

DROP FUNCTION IF EXISTS mms_calc_movement_calculated$$
CREATE FUNCTION mms_calc_movement_calculated(pi_userid          INT(10)    UNSIGNED
                                            ,pi_month           TINYINT(4) UNSIGNED
                                            ,pi_year            YEAR(4)
                                            ,pi_capitalsourceid INT(10)
                                            ) RETURNS  FLOAT(8,2)
BEGIN
  DECLARE l_found      BOOLEAN DEFAULT TRUE;
  DECLARE l_date_begin DATE;
  DECLARE l_date_end   DATE;
  DECLARE l_amount     FLOAT(8,2);
  
  DECLARE c_mmf CURSOR FOR
    SELECT IFNULL(SUM(calc_amount(amount
                                 ,'OUT'
                                 ,pi_userid
                                 ,invoicedate)),0)
      FROM moneyflows
     WHERE bookingdate   BETWEEN l_date_begin AND l_date_end
       AND mur_userid          = pi_userid
       AND mcs_capitalsourceid = pi_capitalsourceid;

  DECLARE CONTINUE HANDLER FOR NOT FOUND SET l_amount := 0;

  SET l_date_begin := STR_TO_DATE(CONCAT(pi_year,'-',pi_month,'-01'),GET_FORMAT(DATE,'ISO'));
  SET l_date_end   := LAST_DAY(l_date_begin);
  
  OPEN  c_mmf;
  FETCH c_mmf INTO l_amount;
  CLOSE c_mmf;

  RETURN l_amount;
END;
$$


-- PROCEDURES

DROP PROCEDURE IF EXISTS user_delete$$
CREATE PROCEDURE user_delete (IN  pi_userid INT(10) UNSIGNED
                             ,OUT po_ret    INT(1) UNSIGNED
                             )
BEGIN
  DECLARE l_num INT(1);
  DECLARE EXIT HANDLER FOR SQLEXCEPTION ROLLBACK;

  SET po_ret := 0;

  START TRANSACTION;
  
  DELETE FROM moneyflows
   WHERE mur_userid = pi_userid;

  DELETE FROM monthlysettlements
   WHERE mur_userid = pi_userid;

  DELETE FROM predefmoneyflows
   WHERE mur_userid = pi_userid;

  DELETE FROM capitalsources
   WHERE mur_userid = pi_userid;

  DELETE FROM contractpartners
   WHERE mur_userid = pi_userid;

  DELETE FROM settings
   WHERE mur_userid = pi_userid;
  
  DELETE FROM users
   WHERE userid = pi_userid;

  COMMIT;

  SET po_ret := 1;

END;$$

DROP PROCEDURE IF EXISTS mms_init_movement_calculated$$
CREATE PROCEDURE mms_init_movement_calculated(IN pi_userid INT(10) UNSIGNED)
BEGIN
  DECLARE l_found           BOOLEAN             DEFAULT TRUE;
  DECLARE l_month           TINYINT(4) UNSIGNED;
  DECLARE l_year            YEAR(4);
  DECLARE l_capitalsourceid INT(10);

  DECLARE c_mms CURSOR FOR
    SELECT month
          ,year
	  ,mcs_capitalsourceid
      FROM monthlysettlements
     WHERE mur_userid = pi_userid;

  DECLARE CONTINUE HANDLER FOR NOT FOUND SET l_found := FALSE ;

  OPEN c_mms;
  REPEAT
    FETCH c_mms INTO l_month, l_year, l_capitalsourceid;
    IF l_found THEN
      UPDATE monthlysettlements
         SET movement_calculated = mms_calc_movement_calculated(pi_userid, l_month, l_year, l_capitalsourceid)
       WHERE month		 = l_month
         AND year		 = l_year
         AND mcs_capitalsourceid = l_capitalsourceid
         AND mur_userid 	 = pi_userid;
    END IF;
  UNTIL NOT l_found END REPEAT;
  CLOSE c_mms;
END;
$$

DROP PROCEDURE IF EXISTS mmf_trg_procedure$$
CREATE PROCEDURE mmf_trg_procedure(IN pi_bookingdate     DATE
                                  ,IN pi_userid          INT(10) UNSIGNED
				  ,IN pi_capitalsourceid INT(10) UNSIGNED
                                  )
BEGIN
  DECLARE l_found  BOOLEAN             DEFAULT TRUE;
  DECLARE l_dummy  INT(1)     UNSIGNED;
  DECLARE l_month  TINYINT(4) UNSIGNED DEFAULT MONTH(pi_bookingdate);
  DECLARE l_year   YEAR(4)             DEFAULT YEAR(pi_bookingdate);

  DECLARE c_mms CURSOR FOR
    SELECT COUNT(*)
      FROM monthlysettlements
     WHERE mur_userid = pi_userid
       AND month      = l_month
       AND year       = l_year;

  OPEN  c_mms;
  FETCH c_mms INTO l_dummy;
  CLOSE c_mms;
  
  IF l_dummy > 0 THEN
    UPDATE monthlysettlements
       SET movement_calculated = mms_calc_movement_calculated(pi_userid, l_month, l_year, pi_capitalsourceid)
     WHERE month               = l_month
       AND year	               = l_year
       AND mcs_capitalsourceid = pi_capitalsourceid
       AND mur_userid          = pi_userid;
  END IF;
END;
$$


-- TRIGGERS

DROP TRIGGER IF EXISTS mpm_trg_01$$
CREATE TRIGGER mpm_trg_01 BEFORE INSERT ON predefmoneyflows
  FOR EACH ROW BEGIN
    SET NEW.createdate = NOW();
  END;
$$

DROP TRIGGER IF EXISTS mmf_trg_01$$
CREATE TRIGGER mmf_trg_01 AFTER INSERT ON moneyflows
  FOR EACH ROW BEGIN
    CALL mmf_trg_procedure(NEW.bookingdate
                          ,NEW.mur_userid
                          ,NEW.mcs_capitalsourceid
                          );
  END;
$$

DROP TRIGGER IF EXISTS mmf_trg_02$$
CREATE TRIGGER mmf_trg_02 AFTER UPDATE ON moneyflows
  FOR EACH ROW BEGIN
    IF OLD.amount != NEW.amount THEN
      CALL mmf_trg_procedure(NEW.bookingdate
                            ,NEW.mur_userid
                            ,NEW.mcs_capitalsourceid
                            );
    END IF;
  END;
$$

DROP TRIGGER IF EXISTS mmf_trg_03$$
CREATE TRIGGER mmf_trg_03 AFTER DELETE ON moneyflows
  FOR EACH ROW BEGIN
    CALL mmf_trg_procedure(OLD.bookingdate
                          ,OLD.mur_userid
                          ,OLD.mcs_capitalsourceid
                          );
  END;
$$

DROP TRIGGER IF EXISTS mms_trg_01$$
CREATE TRIGGER mms_trg_01 BEFORE INSERT ON monthlysettlements
  FOR EACH ROW BEGIN
    SET NEW.movement_calculated := mms_calc_movement_calculated(NEW.mur_userid
                                                               ,NEW.month
                                                               ,NEW.year
                                                               ,NEW.mcs_capitalsourceid
                                                               );
  END;
$$

DROP TRIGGER IF EXISTS mse_trg_01$$
CREATE TRIGGER mse_trg_01 AFTER UPDATE ON settings
  FOR EACH ROW BEGIN
    IF NEW.name   = 'displayed_currency' AND
       NEW.value != OLD.value THEN
      CALL mms_init_movement_calculated(NEW.mur_userid);
    END IF;
  END;
$$

DELIMITER ;


