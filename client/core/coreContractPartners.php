<?php

/*
	$Id: coreContractPartners.php,v 1.5 2005/03/09 20:20:51 olivleh1 Exp $
*/

require_once 'core/core.php';
require_once 'core/coreMoneyFlows.php';

class coreContractPartners extends core {

	function coreContractPartners() {
		$this->core();
	}

	function get_all_data() {
		return $this->select_rows( 'SELECT * FROM contractpartners ORDER BY name' );
	}

	function get_id_data( $id ) {
		return $this->select_row( "SELECT * FROM contractpartners WHERE id=$id" );
	}

	function get_all_index_letters() {
		return $this->select_cols( 'SELECT DISTINCT UPPER(SUBSTR(name,1,1)) letters FROM contractpartners ORDER BY letters' );
	}

	function get_ids_index_letters( $ids ) {
		$idstring=implode( $ids, ',' );
		return $this->select_cols( "SELECT DISTINCT UPPER(SUBSTR(name,1,1)) letters FROM contractpartners WHERE id IN ($idstring) ORDER BY letters" );
	}

	function get_all_matched_data( $letter ) {
		return $this->select_rows( "SELECT * FROM contractpartners WHERE UPPER(name) LIKE UPPER('$letter%') ORDER BY name" );
	}

	function get_ids_matched_data( $letter ) {
		return $this->select_cols( "SELECT id FROM contractpartners WHERE UPPER(name) LIKE UPPER('$letter%') ORDER BY name" );
	}

	function get_all_names() {
		return $this->select_rows( 'SELECT id,name FROM contractpartners ORDER BY name' );
	}

	function get_name( $id ) {
		return $this->select_col( "SELECT name FROM contractpartners WHERE ID=$id" );
	}


	function delete_contractpartner( $id ) {
		$coreMoneyFlows=new coreMoneyFlows();
		if( $coreMoneyFlows->contractpartner_in_use( $id ) ) {
			$this->add_error( "You can't delete a contract partner who is still in use!" );
			return 0;
		} else {
			return $this->delete_row( "DELETE FROM contractpartners WHERE id=$id LIMIT 1" );
		}
		exit;
	}


	function update_contractpartner( $id, $name, $street, $postcode, $town, $country ) {
		return $this->update_row( "UPDATE contractpartners set name='$name',street='$street',postcode='$postcode',town='$town',country='$country' WHERE id=$id" );
	}

	function add_contractpartner( $name, $street, $postcode, $town, $country ) {
		return $this->insert_row( "INSERT INTO contractpartners (name,street,postcode,town,country) VALUES ('$name','$street','$postcode','$town','$country')" );
	}
}
