<?php

/*
	$Id: coreCurrencies.php,v 1.1 2006/12/18 12:24:10 olivleh1 Exp $
*/

require_once 'core/core.php';
require_once 'core/coreSettings.php';

class coreCurrencies extends core {

	function coreCurrencies() {
		$this->core();
		$this->coreSettings = new coreSettings();
	}

	function get_default_currency() {
		return $this->select_col( 'SELECT currency FROM currencies WHERE att_default=1' );
	}

	function get_displayed_currency() {
		return $this->get_currency( $this->coreSettings->get_displayed_currency() );
	}

	function get_currency( $id ) {
		return $this->select_col( "SELECT currency FROM currencies WHERE id=$id" );
	}

	function get_rate( $id ) {
		return $this->select_col( "SELECT rate FROM currencies WHERE id=$id" );
	}

	function get_all_data() {
		return $this->select_rows( 'SELECT * FROM currencies' );
	}
}
