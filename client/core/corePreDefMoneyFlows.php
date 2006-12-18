<?php

/*
	$Id: corePreDefMoneyFlows.php,v 1.9 2006/12/18 12:24:11 olivleh1 Exp $
*/

require_once 'core/core.php';
require_once 'core/coreContractPartners.php';

class corePreDefMoneyFlows extends core {

	function corePreDefMoneyFlows() {
		$this->core();
	}

	function get_all_data() {
		return $this->select_rows( 'SELECT id,calc_amount(amount,'."'OUT'".') amount,capitalsourceid,contractpartnerid,comment FROM predefmoneyflows ORDER BY id' );
	}

	function get_valid_data( $validfrom, $validtil ) {
		return $this->select_rows( "SELECT a.id,calc_amount(a.amount,'OUT') amount,a.capitalsourceid,a.contractpartnerid,a.comment FROM predefmoneyflows a, capitalsources b, contractpartners c WHERE a.capitalsourceid=b.id AND validfrom <= '$validfrom' and validtil >= '$validtil' AND a.contractpartnerid=c.id ORDER BY id" );
	}

	function get_id_data( $id ) {
		return $this->select_row( "SELECT id,calc_amount(amount,'OUT') amount,capitalsourceid,contractpartnerid,comment FROM predefmoneyflows WHERE id=$id" );
	}

	function get_capitalsourceid( $id ) {
		return $this->select_col( "SELECT capitalsourceid FROM predefmoneyflows WHERE id=$id" );
	}

	function get_all_index_letters() {
		$coreContractPartners=new coreContractPartners();
		$temp=$this->select_cols( 'SELECT DISTINCT contractpartnerid FROM predefmoneyflows' );
		return $coreContractPartners->get_ids_index_letters( $temp );
	}

	function get_all_matched_data( $letter ) {
		$coreContractPartners=new coreContractPartners();
		$ids=$coreContractPartners->get_ids_matched_data( $letter );
		$idstring=implode( $ids, ',' );
		return $this->select_rows( "SELECT id,calc_amount(amount,'OUT') amount,capitalsourceid,contractpartnerid,comment FROM predefmoneyflows  WHERE contractpartnerid IN ($idstring) ORDER BY comment" );
	}


	function delete_predefmoneyflow( $id ) {
		return $this->delete_row( "DELETE FROM predefmoneyflows WHERE id=$id LIMIT 1" );
	}


	function update_predefmoneyflow( $id, $amount, $capitalsourceid, $contractpartnerid, $comment ) {
		if( fix_amount( $amount ) ) {
			return $this->update_row( "UPDATE predefmoneyflows set amount=calc_amount('$amount','IN'),capitalsourceid='$capitalsourceid',contractpartnerid='$contractpartnerid',comment='$comment' WHERE id=$id" );
		} else {
			return false;
		}
	}

	function add_predefmoneyflow( $amount, $capitalsourceid, $contractpartnerid, $comment ) {
		if( fix_amount( $amount ) ) {
			return $this->insert_row( "INSERT INTO predefmoneyflows (amount,capitalsourceid,contractpartnerid,comment) VALUES (calc_amount('$amount','IN'),'$capitalsourceid','$contractpartnerid','$comment')" );
		} else {
			return false;
		}
	}
}
