<?php

/*
	$Id: coreMonthlySettlement.php,v 1.6 2005/03/06 01:51:22 olivleh1 Exp $
*/

require_once 'core/core.php';

class coreMonthlySettlement extends core {

	function coreMonthlySettlement() {
		$this->core();
	}

	function get_amount( $sourceid, $month, $year) {
		return $this->select_col( "SELECT round(amount,2) FROM monthlysettlements WHERE capitalsourceid=$sourceid AND month=$month AND year=$year LIMIT 1" );
	}

	function monthlysettlement_exists( $month, $year) {
		if( $this->select_col( "SELECT 1 FROM monthlysettlements WHERE month=$month AND year=$year LIMIT 1" ) == 1 )
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
		return mktime( 0, 0, 0, $result['month']+1, 1, $result['year']);
	}


	function delete_amount( $month, $year ) {
		$this->insert_row( "DELETE FROM monthlysettlements WHERE month=$month AND year=$year" );
		return true;
	}


	function set_amount( $sourceid, $month, $year, $amount ) {
		return $this->insert_row( "INSERT INTO monthlysettlements (capitalsourceid,month,year,amount) VALUES ($sourceid,$month,$year,$amount) ON DUPLICATE KEY UPDATE amount=VALUES(amount)" );
	}
}
