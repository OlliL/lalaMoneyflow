<?php

/*
	$Id: coreMoneyFlows.php,v 1.8 2005/03/09 20:20:51 olivleh1 Exp $
*/

require_once 'core/core.php';
require_once 'core/coreCapitalSources.php';

class coreMoneyFlows extends core {

	function coreMoneyFlows() {
		$this->core();
	}

	function get_all_data( $id=0 ) {
		if( $id>0 )
			return $this->select_row( "SELECT * FROM moneyflows WHERE id=$id" );
		else
			return $this->select_rows( 'SELECT * FROM moneyflows ORDER BY id' );


	}

	function get_id_data( $id ) {
		return $this->select_row( "SELECT * FROM moneyflows WHERE id=$id" );
	}

	function get_all_monthly_data( $month, $year ) {
		return $this->select_rows( "SELECT * FROM moneyflows WHERE bookingdate >= '$year-$month-01' AND bookingdate < DATE_ADD('$year-$month-01', INTERVAL 1 MONTH) ORDER BY bookingdate,invoicedate" );
	}

	function get_all_monthly_joined_data( $month, $year ) {
		return $this->select_rows( "SELECT a.id,a.bookingdate,a.invoicedate,a.amount,a.comment,b.name contractpartnername,c.comment capitalsourcecomment FROM moneyflows a, contractpartners b, capitalsources c WHERE a.bookingdate >= '$year-$month-01' AND a.bookingdate < DATE_ADD('$year-$month-01', INTERVAL 1 MONTH) AND a.contractpartnerid=b.id AND a.capitalsourceid=c.id ORDER BY bookingdate,invoicedate" );
	}

	function capitalsource_in_use( $id ) {
		if( $this->select_col( "SELECT COUNT(*) FROM moneyflows WHERE capitalsourceid=$id" ) > 0 )
			return 1;
		else
			return 0;
	}

	function contractpartner_in_use( $id ) {
		if( $this->select_col( "SELECT COUNT(*) FROM moneyflows WHERE contractpartnerid=$id" ) > 0 )
			return 1;
		else
			return 0;
	}

	function get_monthly_capitalsource_movement( $id, $month, $year ) {
		$movement=$this->select_col( "SELECT round(sum(amount),2) FROM moneyflows WHERE bookingdate >= '$year-$month-01' AND bookingdate < DATE_ADD('$year-$month-01', INTERVAL 1 MONTH) AND capitalsourceid=$id" );
		if( empty( $movement ) )
			$movement=0;
		return $movement;
	}

	function get_all_years() {
		return $this->select_cols( 'SELECT DISTINCT YEAR(bookingdate) year FROM moneyflows ORDER BY year ASC' );
	}

	function get_all_months( $year ) {
		return $this->select_cols( "SELECT DISTINCT MONTH(bookingdate) month FROM moneyflows WHERE YEAR(bookingdate) = $year ORDER BY month ASC" );
	}


	function delete_moneyflow( $id ) {
		return $this->delete_row( "DELETE FROM moneyflows WHERE id=$id LIMIT 1" );
	}


	function update_moneyflow( $id, $bookingdate, $invoicedate, $amount, $capitalsourceid, $contractpartnerid, $comment ) {
		$coreCapitalSources = new coreCapitalSources();
		if( $coreCapitalSources->id_is_valid( $capitalsourceid, $bookingdate ) ) {
			return $this->update_row( "UPDATE moneyflows set bookingdate=STR_TO_DATE('$bookingdate',GET_FORMAT(DATE,'ISO')),invoicedate=STR_TO_DATE('$invoicedate',GET_FORMAT(DATE,'ISO')),amount='$amount',capitalsourceid='$capitalsourceid',contractpartnerid='$contractpartnerid',comment='$comment' WHERE id=$id" );
		} else {
			$this->add_error( "You can't select the capital source you've choosen. It is not valid on the bookingdate you've given" );
			return false;
		}
	}

	function add_moneyflow( $bookingdate, $invoicedate, $amount, $capitalsourceid, $contractpartnerid, $comment ) {
		return $this->insert_row( "INSERT INTO moneyflows (bookingdate,invoicedate,amount,capitalsourceid,contractpartnerid,comment) VALUES (STR_TO_DATE('$bookingdate',GET_FORMAT(DATE,'ISO')),STR_TO_DATE('$invoicedate',GET_FORMAT(DATE,'ISO')),'$amount','$capitalsourceid','$contractpartnerid','$comment')" );
	}
}
