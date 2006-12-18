<?php

/*
	$Id: coreSettings.php,v 1.1 2006/12/18 12:24:11 olivleh1 Exp $
*/

require_once 'core/core.php';

class coreSettings extends core {

	function coreSettings() {
		$this->core();
	}

	function get_displayed_currency() {
		return $this->select_col( "SELECT value FROM settings WHERE name='displayed_currency'" );
	}
}
