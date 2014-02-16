/* 
 * this view will show all possible permutations of user/groups
 */
CREATE OR REPLACE SQL SECURITY INVOKER VIEW vw_user_groups (
   mug1_mur_userid
  ,mug2_mur_userid
  ,validfrom
  ,validtil
  ) AS
     SELECT DISTINCT
            mug1.mur_userid mug1_mur_userid
           ,mug2.mur_userid mug2_mur_userid
           ,mug1.validfrom
           ,mug1.validtil
       FROM user_groups mug1
           ,user_groups mug2
      WHERE mug1.mgr_groupid = mug2.mgr_groupid;
               
               
/*
 * this view will show all data from all users which are in the
 * same group as mur_userid. Use mug_mur_userid in the query,
 * mur_userid is the real userid of the dataset
 */
CREATE OR REPLACE SQL SECURITY INVOKER VIEW vw_moneyflows (
   mur_userid
  ,mug_mur_userid
  ,moneyflowid         
  ,bookingdate
  ,invoicedate          
  ,amount               
  ,mcs_capitalsourceid  
  ,mcp_contractpartnerid
  ,comment              
  ,mpa_postingaccountid
  ,private              
  ) AS
      SELECT mmf.mur_userid
            ,mug.mug2_mur_userid
            ,mmf.moneyflowid
            ,mmf.bookingdate
            ,mmf.invoicedate
            ,mmf.amount
            ,mmf.mcs_capitalsourceid
            ,mmf.mcp_contractpartnerid
            ,mmf.comment
            ,mmf.mpa_postingaccountid
            ,mmf.private
        FROM moneyflows     mmf
            ,vw_user_groups mug
       WHERE (     mug.mug1_mur_userid = mmf.mur_userid
               AND mmf.bookingdate BETWEEN mug.validfrom and mug.validtil
             )
          OR (     mug.mug1_mur_userid = mmf.mur_userid
               AND mug.mug2_mur_userid = mmf.mur_userid
             );

/*
 * this view will show all data from all users which are in the
 * same group as mur_userid. Use mug_mur_userid in the query,
 * mur_userid is the real userid of the dataset
 */
CREATE OR REPLACE SQL SECURITY INVOKER VIEW vw_monthlysettlements (
   mur_userid
  ,mug_mur_userid
  ,monthlysettlementid
  ,mcs_capitalsourceid
  ,month
  ,year
  ,amount
  ,movement_calculated
  ) AS
      SELECT mms.mur_userid
            ,mug.mug2_mur_userid
            ,mms.monthlysettlementid
            ,mms.mcs_capitalsourceid
            ,mms.month
            ,mms.year
            ,mms.amount
            ,mms.movement_calculated
        FROM monthlysettlements mms
            ,vw_user_groups     mug
       WHERE (     mug.mug1_mur_userid = mms.mur_userid
               AND LAST_DAY(STR_TO_DATE(CONCAT(year,'-',LPAD(month,2,'0'),'-01'),GET_FORMAT(DATE,'ISO'))) BETWEEN mug.validfrom and mug.validtil
             )
          OR (     mug.mug1_mur_userid = mms.mur_userid
               AND mug.mug2_mur_userid = mms.mur_userid
             );

/*
 * this view will show all data from all users which are in the
 * same group as mur_userid. Use mug_mur_userid in the query,
 * mur_userid is the real userid of the dataset
 */
CREATE OR REPLACE SQL SECURITY INVOKER VIEW vw_postingaccounts (
   mur_userid
  ,mug_mur_userid
  ,postingaccountid
  ,postingaccountname
  ) AS
      SELECT mpa.mur_userid
            ,mug.mug2_mur_userid
            ,mpa.postingaccountid
            ,mpa.postingaccountname
        FROM postingaccounts  mpa
            ,vw_user_groups   mug
       WHERE mug.mug1_mur_userid = mpa.mur_userid;

/*
 * this view will show all data from all users which are in the
 * same group as mur_userid. Use mug_mur_userid in the query,
 * mur_userid is the real userid of the dataset
 */
CREATE OR REPLACE SQL SECURITY INVOKER VIEW vw_contractpartners (
   mur_userid
  ,mug_mur_userid
  ,contractpartnerid
  ,name
  ,street
  ,postcode
  ,town
  ,country
  ) AS
      SELECT mcp.mur_userid
            ,mug.mug2_mur_userid
            ,mcp.contractpartnerid
            ,mcp.name
            ,mcp.street
            ,mcp.postcode
            ,mcp.town
            ,mcp.country
        FROM contractpartners mcp
            ,vw_user_groups   mug
       WHERE mug.mug1_mur_userid = mcp.mur_userid;

/*
 * this view will show all data from all users which are in the
 * same group as mur_userid. Use mug_mur_userid in the query,
 * mur_userid is the real userid of the dataset
 */
CREATE OR REPLACE SQL SECURITY INVOKER VIEW vw_capitalsources (
   mur_userid
  ,mug_mur_userid
  ,capitalsourceid
  ,type
  ,state
  ,accountnumber
  ,bankcode
  ,comment
  ,validtil
  ,validfrom
  ,att_group_use
  ) AS
      SELECT mcs.mur_userid
            ,mug.mug2_mur_userid
            ,mcs.capitalsourceid
            ,mcs.type
            ,mcs.state
            ,mcs.accountnumber
            ,mcs.bankcode
            ,mcs.comment
            ,(CASE
                WHEN mug.mug2_mur_userid != mcs.mur_userid AND mcs.validtil  > mug.validtil THEN mug.validtil
                ELSE mcs.validtil
              END)
            ,(CASE
                WHEN mug.mug2_mur_userid != mcs.mur_userid AND mcs.validfrom < mug.validfrom THEN mug.validfrom
                ELSE mcs.validfrom
              END)
            ,mcs.att_group_use
        FROM capitalsources     mcs
            ,vw_user_groups     mug
       WHERE (     mug.mug1_mur_userid = mcs.mur_userid
               AND mcs.validfrom < mug.validtil
               AND mcs.validtil  > mug.validfrom
             )
          OR (     mug.mug1_mur_userid = mcs.mur_userid
               AND mug.mug2_mur_userid = mcs.mur_userid
             );

-- FUNCTIONS

/*
 * this function checks if there still exists data
 * which has to be done before dropping a user to avoid
 * an foreign key constraint violation (and to alert the
 * admin that he is about to drop a user who still owns
 * data
 */
DROP FUNCTION IF EXISTS user_owns_data$$
CREATE FUNCTION user_owns_data (pi_userid INT(10) UNSIGNED
                               ) RETURNS  INT(1)
READS SQL DATA
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

/*
 * this function adds a new language by copying the
 * text from a "source" language
 */
DROP FUNCTION IF EXISTS add_language$$
CREATE FUNCTION add_language (pi_language VARCHAR(10)
                             ,pi_source   INT(10) UNSIGNED
                             ) RETURNS  INT(10)
READS SQL DATA
BEGIN
  DECLARE l_languageid INT(10);
  
  INSERT INTO languages (language
                        )
                          VALUES
                        (pi_language
                        );

  SELECT LAST_INSERT_ID()
    INTO l_languageid
    FROM DUAL;

  INSERT INTO text (textid
                   ,mla_languageid
                   ,text
                   ,type
                   )
                   SELECT textid
                         ,l_languageid
                         ,text
                         ,type
                     FROM text
		    WHERE mla_languageid = pi_source;

  RETURN l_languageid;
  
END;$$

/*
 * this function returns the calculated movment in
 * a given month/year combination to store it for
 * example into monthlysettlements.movement_calculated
 */
DROP FUNCTION IF EXISTS mms_calc_movement_calculated$$
CREATE FUNCTION mms_calc_movement_calculated(pi_userid          INT(10)    UNSIGNED
                                            ,pi_month           TINYINT(4) UNSIGNED
                                            ,pi_year            YEAR(4)
                                            ,pi_capitalsourceid INT(10)
                                            ) RETURNS  FLOAT(8,2)
READS SQL DATA
BEGIN
  DECLARE l_found      BOOLEAN DEFAULT TRUE;
  DECLARE l_date_begin DATE;
  DECLARE l_date_end   DATE;
  DECLARE l_amount     FLOAT(8,2);
  
  DECLARE c_mmf CURSOR FOR
    SELECT IFNULL(SUM(amount),0)
      FROM vw_moneyflows
     WHERE bookingdate   BETWEEN l_date_begin AND l_date_end
       AND mug_mur_userid      = pi_userid
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

/*
 * this procedure deletes a user by first deleting all the data
 * the user might own and then finally deleting the user from
 * the table users
 */
DROP PROCEDURE IF EXISTS user_delete$$
CREATE PROCEDURE user_delete (IN  pi_userid INT(10) UNSIGNED
                             ,OUT po_ret    INT(1) UNSIGNED
                             )
READS SQL DATA
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

/*
 * this procedure fills the column monthlysettlements.movement_calculated
 * when the settlement gets created the first time
 */
DROP PROCEDURE IF EXISTS mms_init_movement_calculated$$
CREATE PROCEDURE mms_init_movement_calculated(IN pi_userid INT(10) UNSIGNED)
READS SQL DATA
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
       WHERE month               = l_month
         AND year                = l_year
         AND mcs_capitalsourceid = l_capitalsourceid
         AND mur_userid          = pi_userid;
    END IF;
  UNTIL NOT l_found END REPEAT;
  CLOSE c_mms;
END;
$$

/*
 * this procedure is used to change the movement_calculated
 * column in table monthlysettlements if a moneyflow got
 * changed, inserted or deleted
 */
DROP PROCEDURE IF EXISTS mmf_trg_procedure$$
CREATE PROCEDURE mmf_trg_procedure(IN pi_bookingdate     DATE
                                  ,IN pi_userid          INT(10) UNSIGNED
                                  ,IN pi_capitalsourceid INT(10) UNSIGNED
                                  )
READS SQL DATA
BEGIN
  DECLARE l_found       BOOLEAN             DEFAULT TRUE;
  DECLARE l_mur_userid  INT(1)     UNSIGNED;
  DECLARE l_month       TINYINT(4) UNSIGNED DEFAULT MONTH(pi_bookingdate);
  DECLARE l_year        YEAR(4)             DEFAULT YEAR(pi_bookingdate);

  DECLARE c_mms CURSOR FOR
    SELECT mur_userid
      FROM vw_monthlysettlements
     WHERE mug_mur_userid = pi_userid
       AND month          = l_month
       AND year           = l_year;

  DECLARE CONTINUE HANDLER FOR NOT FOUND        SET l_mur_userid := 0;

  OPEN  c_mms;
  FETCH c_mms INTO l_mur_userid;
  CLOSE c_mms;
  
  IF l_mur_userid > 0 THEN
    UPDATE monthlysettlements
       SET movement_calculated = mms_calc_movement_calculated(pi_userid, l_month, l_year, pi_capitalsourceid)
     WHERE month               = l_month
       AND year                = l_year
       AND mcs_capitalsourceid = pi_capitalsourceid;
  END IF;
END;
$$

/*
 * import data which is stored in table imp_data
 * and try to map the capitalsource and the contractpartner
 * if possible to the internal IDs (External data comes as
 * text instead of IDs)
 */
DROP PROCEDURE IF EXISTS imp_moneyflows$$
CREATE PROCEDURE imp_moneyflows (IN pi_userid   INT(10) UNSIGNED
                                ,IN pi_write    INT(1)  UNSIGNED
                                )
READS SQL DATA
BEGIN
  DECLARE l_found             BOOLEAN             DEFAULT TRUE;
  DECLARE l_insert            BOOLEAN             DEFAULT TRUE;
  DECLARE l_contractpartnerid INT(10);
  DECLARE l_capitalsourceid   INT(10);
  DECLARE l_dataid            INT(10);
  DECLARE l_date              VARCHAR(100);
  DECLARE l_amount            VARCHAR(100);
  DECLARE l_comment           VARCHAR(100);

  DECLARE c_mid CURSOR FOR
    SELECT mcp.contractpartnerid
          ,mcs.capitalsourceid
          ,mid.dataid
          ,mid.date
          ,mid.amount
          ,mid.comment
      FROM                 imp_data            mid
           LEFT OUTER JOIN imp_mapping_source  mis ON mid.source  LIKE mis.source_from
           LEFT OUTER JOIN imp_mapping_partner mip ON mid.partner = mip.partner_from
           LEFT OUTER JOIN capitalsources      mcs ON IFNULL(mis.source_to,mid.source)   = mcs.comment AND mcs.mur_userid = pi_userid
           LEFT OUTER JOIN contractpartners    mcp ON IFNULL(mip.partner_to,mid.partner) = mcp.name    AND mcp.mur_userid = pi_userid
     WHERE mid.status = 1;

  DECLARE CONTINUE HANDLER FOR NOT FOUND        SET l_found  := FALSE;
  DECLARE CONTINUE HANDLER FOR SQLSTATE '23000' SET l_insert := FALSE;

  UPDATE imp_data SET status=1 WHERE status=3;

  OPEN c_mid;
  REPEAT
    FETCH c_mid INTO l_contractpartnerid, l_capitalsourceid, l_dataid, l_date, l_amount, l_comment;
    IF l_found THEN

      START TRANSACTION;

      SET l_insert := TRUE;

      INSERT INTO moneyflows
            (mur_userid
            ,bookingdate
            ,invoicedate
            ,amount
            ,mcs_capitalsourceid
            ,mcp_contractpartnerid
            ,comment
            )
              VALUES
            (pi_userid
            ,str_to_date(l_date,'%d.%m.%Y')
            ,str_to_date(l_date,'%d.%m.%Y')
            ,l_amount
            ,l_capitalsourceid
            ,l_contractpartnerid
            ,l_comment
            );

      IF pi_write = 1 AND l_insert = TRUE THEN
        COMMIT;
      ELSE
        ROLLBACK;
      END IF;
      
      START TRANSACTION;
      IF l_insert = FALSE THEN
        UPDATE imp_data 
           SET status = 3
         WHERE dataid = l_dataid;
      ELSE
        UPDATE imp_data 
           SET status = 2
         WHERE dataid = l_dataid;
      END IF;
      COMMIT;

    END IF;
  UNTIL NOT l_found END REPEAT;
  CLOSE c_mid;
END;
$$


-- TRIGGERS

/* journalling on predefmoneyflows */
DROP TRIGGER IF EXISTS mpm_trg_01$$
CREATE TRIGGER mpm_trg_01 BEFORE INSERT ON predefmoneyflows
  FOR EACH ROW BEGIN
    SET NEW.createdate = NOW();
  END;
$$

/*
 * when a new moneyflow got inserted, recalculate
 * monthlysettlements.movement_calculated
 */
DROP TRIGGER IF EXISTS mmf_trg_01$$
CREATE TRIGGER mmf_trg_01 AFTER INSERT ON moneyflows
  FOR EACH ROW BEGIN
    CALL mmf_trg_procedure(NEW.bookingdate
                          ,NEW.mur_userid
                          ,NEW.mcs_capitalsourceid
                          );
  END;
$$

/*
 * when a moneyflow got changed, recalculate
 * monthlysettlements.movement_calculated for the
 * new month (can be the same) or for the new and old
 * month if the date of  the moneyflow got changed or
 * the capitalsource.
 */
DROP TRIGGER IF EXISTS mmf_trg_02$$
CREATE TRIGGER mmf_trg_02 AFTER UPDATE ON moneyflows
  FOR EACH ROW BEGIN
    DECLARE l_calc_new    BOOLEAN             DEFAULT FALSE;
    DECLARE l_calc_old    BOOLEAN             DEFAULT FALSE;
    
    IF OLD.amount                != NEW.amount                THEN
      SET l_calc_new := TRUE;
    END IF;
    
    IF LAST_DAY(OLD.bookingdate) != LAST_DAY(NEW.bookingdate) OR
       OLD.mcs_capitalsourceid   != NEW.mcs_capitalsourceid   THEN
      SET l_calc_old := TRUE;
      SET l_calc_new := TRUE;
    END IF;
       
    IF l_calc_old THEN
      CALL mmf_trg_procedure(OLD.bookingdate
                            ,OLD.mur_userid
                            ,OLD.mcs_capitalsourceid
                            );
    END IF;

    IF l_calc_new THEN
      CALL mmf_trg_procedure(NEW.bookingdate
                            ,NEW.mur_userid
                            ,NEW.mcs_capitalsourceid
                            );
    END IF;
  END;
$$

/*
 * when a moneyflow gets deleted, recalculate
 * monthlysettlements.movement_calculated
 */
DROP TRIGGER IF EXISTS mmf_trg_03$$
CREATE TRIGGER mmf_trg_03 AFTER DELETE ON moneyflows
  FOR EACH ROW BEGIN
    CALL mmf_trg_procedure(OLD.bookingdate
                          ,OLD.mur_userid
                          ,OLD.mcs_capitalsourceid
                          );
  END;
$$

/*
 * when a new settlement gets created, init
 * monthlysettlements.movement_calculated
 */
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

DELIMITER ;


