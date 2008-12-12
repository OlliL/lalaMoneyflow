<?php
#-
# Copyright (c) 2005-2007 Oliver Lehmann <oliver@FreeBSD.org>
# All rights reserved.
#
# Redistribution and use in source and binary forms, with or without
# modification, are permitted provided that the following conditions
# are met:
# 1. Redistributions of source code must retain the above copyright
#	notice, this list of conditions and the following disclaimer
# 2. Redistributions in binary form must reproduce the above copyright
#	notice, this list of conditions and the following disclaimer in the
#	documentation and/or other materials provided with the distribution.
#
# THIS SOFTWARE IS PROVIDED BY THE AUTHOR AND CONTRIBUTORS ``AS IS'' AND
# ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
# IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
# ARE DISCLAIMED.  IN NO EVENT SHALL THE AUTHOR OR CONTRIBUTORS BE LIABLE
# FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
# DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS
# OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION)
# HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
# LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY
# OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF
# SUCH DAMAGE.
#
# $Id: coreMonthlySettlement.php,v 1.23 2008/12/12 19:51:06 olivleh1 Exp $
#

require_once 'core/core.php';

class coreMonthlySettlement extends core {

	function coreMonthlySettlement() {
		$this->core();
	}

	function get_data( $sourceid, $month, $year ) {
		$date = $this->make_date( $year."-".$month."-01" );
		return $this->select_row( "	SELECT calc_amount(amount,'OUT',mur_userid,LAST_DAY($date)) amount
						      ,movement_calculated
						  FROM monthlysettlements
						 WHERE mcs_capitalsourceid = $sourceid
						   AND month               = $month
						   AND year                = $year
						   AND mur_userid          = ".USERID."
						 LIMIT 1" );
	}

	function get_sum_data( $month, $year ) {
		$date = $this->make_date( $year."-".$month."-01" );
		return $this->select_row( "	SELECT SUM(calc_amount(amount,'OUT',mur_userid,LAST_DAY($date))) amount
						      ,SUM(movement_calculated) movement_calculated
						  FROM monthlysettlements
						 WHERE month      = $month
						   AND year       = $year
						   AND mur_userid = ".USERID."
						 LIMIT 1" );
	}

	function get_amount( $sourceid, $month, $year ) {
		$result = $this->get_data( $sourceid, $month, $year );
		return $result['amount'];
	}

	function get_sum_amount( $month, $year ) {
		$result = $this->get_sum_data( $month, $year );
		return $result['amount'];
	}

	function get_movement_calculated( $sourceid, $month, $year ) {
		$date = $this->make_date( $year."-".$month."-01" );
		return $this->select_row( "	SELECT movement_calculated
						  FROM monthlysettlements
						 WHERE mcs_capitalsourceid = $sourceid
						   AND month               = $month
						   AND year                = $year
						   AND mur_userid          = ".USERID."
						 LIMIT 1" );
	}

	function monthlysettlement_exists( $month, $year, $sourceid = 0 ) {
		if( is_array($sourceid) ) {
			$result = $this->select_col( "	SELECT 1
							  FROM monthlysettlements
							 WHERE mcs_capitalsourceid IN (".implode($sourceid,',').")
							   AND month      = $month
							   AND year       = $year
							   AND mur_userid = ".USERID."
							 LIMIT 1" );
			
		} elseif( $sourceid == 0 )
			$result = $this->select_col( "	SELECT 1
							  FROM monthlysettlements
							 WHERE month      = $month
							   AND year       = $year
							   AND mur_userid = ".USERID."
							 LIMIT 1" );
		else
			$result = $this->select_col( "	SELECT 1
							  FROM monthlysettlements
							 WHERE mcs_capitalsourceid = $sourceid
							   AND month               = $month
							   AND year                = $year
							   AND mur_userid          = ".USERID."
							 LIMIT 1" );

		if( $result === '1' )
			return true;
		else
			return false;
	}

	function get_all_years() {
		return $this->select_cols( '	SELECT DISTINCT year
						  FROM monthlysettlements
						 WHERE mur_userid = '.USERID.'
						 ORDER BY year ASC' );
	}

	function get_all_months( $year ) {
		return $this->select_cols( "     SELECT DISTINCT month
						   FROM monthlysettlements
						  WHERE year       = $year
						    AND mur_userid = ".USERID."
						  ORDER BY month ASC" );
	}

	function get_next_date() {
		$result = $this->select_row( '	SELECT MAX(month) month
						      ,MAX(year)  year
						  FROM monthlysettlements
						 WHERE year       = (SELECT MAX(year)
						                       FROM monthlysettlements
						                      WHERE mur_userid = '.USERID.')
						   AND mur_userid = '.USERID.'' );
		if( !empty( $result['month'] ) && !empty( $result['year'] ) ) {
			return mktime( 0, 0, 0, $result['month']+1, 1, $result['year'] );
		} else {
			return false;
		} 
	}


	function get_year_movement( $month, $year ) {

		$ret = $this->select_row( "	SELECT SUM(movement_calculated) movement_calculated
						      ,MAX(month) month
						  FROM monthlysettlements
						 WHERE mur_userid = ".USERID."
						   AND year   = $year
						   AND month <= $month" );
		return $ret;
	}

	function delete_monthlysettlement( $month, $year ) {
		$this->delete_row( "     DELETE FROM monthlysettlements
					  WHERE month      = $month
					    AND year       = $year
					    AND mur_userid = ".USERID );
		return true;
	}


	function update_monthlysettlement( $sourceid, $month, $year, $amount ) {
		$date = $this->make_date( $year."-".$month."-01" );
		if( fix_amount( $amount ) ) {
			return $this->update_row("	UPDATE monthlysettlements
							   SET amount = calc_amount($amount,'IN',".USERID.",LAST_DAY($date))
							 WHERE month               = $month
							   AND year                = $year
							   AND mcs_capitalsourceid = $sourceid");
		} else {
			return false;
		}
	}
	
	function insert_monthlysettlement( $sourceid, $month, $year, $amount ) {
		$date = $this->make_date( $year."-".$month."-01" );
		if( fix_amount( $amount ) ) {
			return $this->insert_row( "	INSERT INTO monthlysettlements
							      (mur_userid
							      ,mcs_capitalsourceid
							      ,month
							      ,year
							      ,amount
							      )
							       VALUES
							      (".USERID."
							      ,$sourceid
							      ,$month
							      ,$year
							      ,calc_amount($amount,'IN',".USERID.",LAST_DAY($date))
							      )" );
		} else {
			return false;
		}
	}
}
