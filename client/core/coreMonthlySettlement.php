<?php

require_once 'core/core.php';

class coreMonthlySettlement extends core {

	function coreMonthlySettlement() {
		$this->core();
	}
	
#	function get_all_monthly_data($month,$year) {
#		return $this->select_rows( "SELECT * FROM monthlysettlement WHERE month=$month AND year=$year order by capitalsourceid" );
#	}

	function get_amount($sourceid,$month,$year) {
		return $this->select_col( "SELECT round(amount,2) FROM monthlysettlement WHERE capitalsourceid=$sourceid AND month=$month AND year=$year LIMIT 1 " );
	}


	function delete_amount($month, $year) {
		return $this->insert_row( "DELETE FROM monthlysettlement WHERE month=$month AND year=$year;" );
	}


	function set_amount($sourceid,$month,$year,$amount) {
		return $this->insert_row( "INSERT INTO monthlysettlement (capitalsourceid,month,year,amount) VALUES ($sourceid,$month,$year,$amount) ON DUPLICATE KEY UPDATE amount=VALUES(amount);" );
	}
}
