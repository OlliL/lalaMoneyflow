<?php

/*
	$Id: coreMonthlySettlement.php,v 1.13 2006/12/18 12:24:11 olivleh1 Exp $
*/

require_once 'core/core.php';

class coreMonthlySettlement extends core {

	function coreMonthlySettlement() {
		$this->core();
	}

	function get_amount( $sourceid, $month, $year ) {
		return $this->select_col( "SELECT calc_amount(amount,'OUT') amount FROM monthlysettlements WHERE capitalsourceid=$sourceid AND month=$month AND year=$year LIMIT 1" );
	}

	function get_sum_amount( $month, $year ) {
		return $this->select_col( "SELECT calc_amount(amount,'OUT')amount FROM monthlysettlements WHERE month=$month AND year=$year LIMIT 1" );
	}

	function monthlysettlement_exists( $month, $year, $sourceid = 0 ) {
		if( $sourceid == 0 )
			$result = $this->select_col( "SELECT 1 FROM monthlysettlements WHERE month=$month AND year=$year LIMIT 1" );
		else
			$result = $this->select_col( "SELECT 1 FROM monthlysettlements WHERE month=$month AND year=$year AND capitalsourceid=$sourceid LIMIT 1" );

		if( $result == 1 )
			return true;
		else
			return false;
	}

	function get_all_years() {
		return $this->select_cols( 'SELECT DISTINCT year FROM monthlysettlements ORDER BY year ASC' );
	}

	function get_all_months( $year ) {
		return $this->select_cols( "SELECT DISTINCT month FROM monthlysettlements WHERE year = $year ORDER BY month ASC" );
	}

	function get_next_date() {
		$result=$this->select_row( 'SELECT MAX(month) month,MAX(year) year FROM monthlysettlements WHERE year=(SELECT MAX(year) FROM monthlysettlements)' );
		return mktime( 0, 0, 0, $result['month']+1, 1, $result['year'] );
	}


	function delete_amount( $month, $year ) {
		$this->insert_row( "DELETE FROM monthlysettlements WHERE month=$month AND year=$year" );
		return true;
	}


	function set_amount( $sourceid, $month, $year, $amount ) {
		if( fix_amount( $amount ) ) {
			return $this->insert_row( "INSERT INTO monthlysettlements (capitalsourceid,month,year,amount) VALUES ($sourceid,$month,$year,calc_amount($amount,'IN')) ON DUPLICATE KEY UPDATE amount=VALUES(amount)" );
		} else {
			return false;
		}
	}
}
