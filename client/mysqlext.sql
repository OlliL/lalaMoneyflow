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
 * this view will show all data from moneyflows which is visible
 * to a user. Use maf_id in your SELECT for your userid. In
 * mac_id_creator you'll find the original userid of the creator
 */
CREATE OR REPLACE SQL SECURITY INVOKER VIEW vw_moneyflows (
   mac_id_creator
  ,maf_id
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
      SELECT mmf.mac_id_creator
            ,maf.id
            ,mmf.moneyflowid
            ,mmf.bookingdate
            ,mmf.invoicedate
            ,mmf.amount
            ,mmf.mcs_capitalsourceid
            ,mmf.mcp_contractpartnerid
            ,mmf.comment
            ,mmf.mpa_postingaccountid
            ,mmf.private
        FROM moneyflows       mmf
            ,access_flattened maf
       WHERE mmf.bookingdate BETWEEN maf.validfrom AND maf.validtil
         AND mmf.mac_id_accessor IN (maf.id_level_1,maf.id_level_2,maf.id_level_3,maf.id_level_4,maf.id_level_5);

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
  ) AS
      SELECT mms.mur_userid
            ,mug.mug2_mur_userid
            ,mms.monthlysettlementid
            ,mms.mcs_capitalsourceid
            ,mms.month
            ,mms.year
            ,mms.amount
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
 * this view will show all data from contractpartners which is visible
 * to a user. Use maf_id in your SELECT for your userid. In
 * mac_id_creator you'll find the original userid of the creator
 */
CREATE OR REPLACE SQL SECURITY INVOKER VIEW vw_contractpartners (
   mac_id_creator
  ,maf_id
  ,contractpartnerid
  ,name
  ,street
  ,postcode
  ,town
  ,country
  ,validfrom
  ,validtil
  ,maf_validfrom
  ,maf_validtil
  ) AS
      SELECT mcp.mac_id_creator
            ,maf.id
            ,mcp.contractpartnerid
            ,mcp.name
            ,mcp.street
            ,mcp.postcode
            ,mcp.town
            ,mcp.country
            ,mcp.validfrom
            ,mcp.validtil
            ,maf.validfrom maf_validfrom
            ,maf.validtil  maf_validtil
        FROM contractpartners mcp
            ,access_flattened maf
       WHERE mcp.mac_id_accessor IN (maf.id_level_1,maf.id_level_2,maf.id_level_3,maf.id_level_4,maf.id_level_5);

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

DELIMITER ;


