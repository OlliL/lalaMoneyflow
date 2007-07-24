<?php
#-
# Copyright (c) 2005-2006 Oliver Lehmann <oliver@FreeBSD.org>
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
# $Id: coreMonthlySettlement.php,v 1.16 2007/07/24 18:22:06 olivleh1 Exp $
#

require_once 'core/core.php';

class coreMonthlySettlement extends core {

	function coreMonthlySettlement() {
		$this->core();
	}

	function get_amount( $sourceid, $month, $year ) {
		$date = $this->make_date( $year."-".$month."-01" );
		return $this->select_col( "	SELECT calc_amount(amount,'OUT',mur_userid,LAST_DAY($date)) amount
						  FROM monthlysettlements
						 WHERE mcs_capitalsourceid = $sourceid
						   AND month               = $month
						   AND year                = $year
						   AND mur_userid          = ".USERID."
						 LIMIT 1" );
	}

	function get_sum_amount( $month, $year ) {
		$date = $this->make_date( $year."-".$month."-01" );
		return $this->select_col( "	SELECT SUM(calc_amount(amount,'OUT',mur_userid,LAST_DAY($date))) amount
						  FROM monthlysettlements
						 WHERE month      = $month
						   AND year       = $year
						   AND mur_userid = ".USERID."
						 LIMIT 1" );
	}

	function monthlysettlement_exists( $month, $year, $sourceid = 0 ) {
		if( $sourceid == 0 )
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

		if( $result == 1 )
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
		return mktime( 0, 0, 0, $result['month']+1, 1, $result['year'] );
	}


	function delete_amount( $month, $year ) {
		$this->insert_row( "     DELETE FROM monthlysettlements
					  WHERE month      = $month
					    AND year       = $year
					    AND mur_userid = ".USERID );
		return true;
	}


	function set_amount( $sourceid, $month, $year, $amount ) {
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
							      )
							    ON DUPLICATE KEY UPDATE amount = VALUES(amount)" );
		} else {
			return false;
		}
	}
}
