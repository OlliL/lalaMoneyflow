<?php

/*
	$Id: corePreDefMoneyFlows.php,v 1.3 2005/03/05 22:54:20 olivleh1 Exp $
*/

require_once 'core/core.php';
require_once 'core/coreContractPartners.php';

class corePreDefMoneyFlows extends core {

	function corePreDefMoneyFlows() {
		$this->core();
		$this->coreContractPartners=new coreContractPartners();
	}
	
	function get_all_data() {
		return $this->select_rows( 'SELECT * FROM predefmoneyflows ORDER BY id' );
	}

	function get_id_data( $id ) {
		return $this->select_row( "SELECT * FROM predefmoneyflows WHERE id=$id" );
	}

	function get_all_index_letters() {
		$temp=$this->select_cols( 'SELECT DISTINCT contractpartnerid FROM predefmoneyflows' );
		return $this->coreContractPartners->get_ids_index_letters( $temp );
	}

	function get_all_matched_data( $letter ) {
		$ids=$this->coreContractPartners->get_ids_matched_data( $letter );
		$idstring=implode( $ids, ',' );
		return $this->select_rows( "SELECT * FROM predefmoneyflows  WHERE contractpartnerid IN ($idstring) ORDER BY comment" );
	}


	function delete_predefmoneyflow( $id ) {
		return $this->delete_row( "DELETE FROM predefmoneyflows WHERE id=$id LIMIT 1" );
	}


	function update_predefmoneyflow( $id, $amount, $capitalsourceid, $contractpartnerid, $comment ) {
		return $this->insert_row( "UPDATE predefmoneyflows set amount='$amount',capitalsourceid='$capitalsourceid',contractpartnerid='$contractpartnerid',comment='$comment' WHERE id=$id" );
	}

	function add_predefmoneyflow( $amount, $capitalsourceid, $contractpartnerid, $comment ) {
		return $this->insert_row( "INSERT INTO predefmoneyflows (amount,capitalsourceid,contractpartnerid,comment) VALUES ('$amount','$capitalsourceid','$contractpartnerid','$comment')" );
	}
}
