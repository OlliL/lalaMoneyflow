<?php

/*
	$Id: coreCapitalSources.php,v 1.8 2005/03/09 20:52:26 olivleh1 Exp $
*/

require_once 'core/core.php';
require_once 'core/coreMoneyFlows.php';

class coreCapitalSources extends core {

	function coreCapitalSources() {
		$this->core();
	}

	function get_all_data() {
		return $this->select_rows( 'SELECT * FROM capitalsources ORDER BY id' );
	}

	function get_id_data( $id ) {
		return $this->select_row( "SELECT * FROM capitalsources WHERE id=$id" );
	}

	function get_all_index_letters() {
		return $this->select_cols( 'SELECT DISTINCT UPPER(SUBSTR(comment,1,1)) letters FROM capitalsources ORDER BY letters' );
	}

	function get_all_matched_data( $letter ) {
		return $this->select_rows( "SELECT * FROM capitalsources WHERE UPPER(comment) LIKE UPPER('$letter%') ORDER BY comment" );
	}

	function get_all_ids() {
		return $this->select_cols( 'SELECT id FROM capitalsources ORDER BY id' );
	}

	function get_valid_ids( $validfrom, $validtil ) {
		return $this->select_cols( "SELECT id FROM capitalsources WHERE validfrom <= '$validfrom' and validtil >= '$validtil' ORDER BY id" );
	}

	function get_all_comments() {
		return $this->select_rows( 'SELECT id,comment FROM capitalsources ORDER BY id' );
	}

	function get_valid_comments( $validfrom, $validtil ) {
		return $this->select_rows( "SELECT id,comment FROM capitalsources WHERE validfrom <= '$validfrom' and validtil >= '$validytil' ORDER BY id" );
	}

	function get_enum_type() {
		return $this->real_get_enum_values( 'capitalsources', 'type' );
	}

	function get_enum_state() {
		return $this->real_get_enum_values( 'capitalsources', 'state' );
	}

	function get_comment( $id ) {
		return $this->select_col( "SELECT comment FROM capitalsources WHERE id=$id LIMIT 1" );
	}

	function get_type( $id ) {
		return $this->select_col( "SELECT type FROM capitalsources WHERE id=$id LIMIT 1" );
	}

	function get_state( $id ) {
		return $this->select_col( "SELECT state FROM capitalsources WHERE id=$id LIMIT 1" );
	}

	function id_is_valid( $id, $date ) {
		return $this->select_col( "SELECT 1 from capitalsources WHERE id=$id AND validfrom <= '$date' and validtil >= '$date' LIMIT 1" );
	}


	function delete_capitalsource( $id ) {
		$coreMoneyFlows=new coreMoneyFlows();
		if( $coreMoneyFlows->capitalsource_in_use( $id ) ) {
			$this->add_error( "You can't delete a capital source which is still in use!" );
			return 0;
		} else {
			return $this->delete_row( "DELETE FROM capitalsources WHERE id=$id LIMIT 1" );
		}
	}


	function update_capitalsource( $id, $type, $state, $accountnumber, $bankcode, $comment, $validfrom, $validtil ) {
		$coreMoneyFlows=new coreMoneyFlows();
		if( $coreMoneyFlows->capitalsource_in_use_out_of_date( $id, $validfrom, $validtil ) ) {
			$this->add_error( "There are some moneyflows out of the validityperiod you wanted to set!" );
			return 0;
		} else {
			return $this->update_row( "UPDATE capitalsources set type='$type',state='$state',accountnumber='$accountnumber',bankcode='$bankcode',comment='$comment',validfrom='$validfrom',validtil='$validtil' WHERE id=$id" );
		}
	}

	function add_capitalsource( $type, $state, $accountnumber, $bankcode, $comment, $validfrom, $validtil ) {
		return $this->insert_row( "INSERT INTO capitalsources (type,state,accountnumber,bankcode,comment,validfrom,validtil) VALUES ('$type','$state','$accountnumber','$bankcode','$comment','$validfrom','$validtil')" );
	}
}
