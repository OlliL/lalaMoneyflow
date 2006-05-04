<?php

/*
	$Id: coreMoneyFlows.php,v 1.14 2006/05/04 19:45:26 olivleh1 Exp $
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
		return $this->select_rows( "SELECT * FROM moneyflows WHERE bookingdate BETWEEN '$year-$month-01' AND DATE_ADD('$year-$month-01', INTERVAL 1 MONTH) ORDER BY bookingdate,invoicedate" );
	}

	function get_all_monthly_joined_data( $month, $year, $sortby, $order ) {
		$sortbyadd=' bookingdate,invoicedate';
		switch( $sortby ) {
			case 'capitalsources_comment':	$sortby='capitalsourcecomment '.$order.',';
							break;
			case 'moneyflows_bookingdate':	$sortby='a.bookingdate '.$order.',';
							$sortbyadd=' invoicedate';
							break;
			case 'moneyflows_invoicedate':	$sortby='a.invoicedate '.$order.',';
							$sortbyadd=' bookingdate';
							break;
			case 'moneyflows_amount':	$sortby='a.amount '.$order.',';
							break;
			case 'moneyflows_comment':	$sortby='a.comment '.$order.',';
							break;
			case 'contractpartners_name':	$sortby='contractpartnername '.$order.',';
							break;
			default:			$sortby='';
		}
		return $this->select_rows( "SELECT a.id,a.bookingdate,a.invoicedate,a.amount,a.comment,b.name contractpartnername,c.comment capitalsourcecomment FROM moneyflows a, contractpartners b, capitalsources c WHERE a.bookingdate >= '$year-$month-01' AND a.bookingdate < DATE_ADD('$year-$month-01', INTERVAL 1 MONTH) AND a.contractpartnerid=b.id AND a.capitalsourceid=c.id ORDER BY $sortby $sortbyadd" );
	}

	function capitalsource_in_use( $id ) {
		if( $this->select_col( "SELECT COUNT(*) FROM moneyflows WHERE capitalsourceid=$id" ) > 0 )
			return 1;
		else
			return 0;
	}

	function capitalsource_in_use_out_of_date( $id, $validfrom, $validtil ) {
		if( $this->select_col( "SELECT COUNT(*) FROM moneyflows WHERE capitalsourceid=$id AND ( bookingdate < '$validfrom' OR bookingdate > '$validtil')" ) > 0 )
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
		$start = "'".$year."-".$month."-01'";
		$end   = "DATE_ADD('$year-$month-01', INTERVAL 1 MONTH)";
		$where = " AND capitalsourceid=$id";

		$movement=$this->select_col( "SELECT round(sum(amount),2) FROM moneyflows WHERE bookingdate >= $start AND bookingdate < $end".$where );
		if( empty( $movement ) )
			$movement=0;
		return $movement;
	}

	function get_year_capitalsource_movement( $month, $year ) {
		$start = "'".$year."-01-01'";
		if( empty( $month ) ) {
			$end   = "'".($year+1)."-01-01'";
		} else {
			$end   = "DATE_ADD('$year-$month-01', INTERVAL 1 MONTH)";
		}

		$movement=$this->select_col( "SELECT round(sum(amount),2) FROM moneyflows WHERE bookingdate >= $start AND bookingdate < $end" );
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

	function get_capitalsourceid( $id ) {
		return $this->select_col( "SELECT capitalsourceid FROM moneyflows WHERE id=$id" );
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

	function search_moneyflows( $params ) {
		$SEARCHCOL="comment";
		if( empty( $params['startdate'] ) )
			$params['startdate'] = '0000-00-00';
		if( empty( $params['enddate'] ) )
			$params['enddate'] = '9999-12-31';

		if( $params["equal"] == 1 ) {
			$LIKE="=";
		} else {
			if( $params["regexp"] == 1 ) {
				$LIKE="REGEXP";
				$params["pattern"] = str_replace("\]","\\\]",$params["pattern"]);
				$params["pattern"] = str_replace("\[","\\\[",$params["pattern"]);
			}
			else
				$LIKE="LIKE";
			if( $params["casesensitive"] == 1 )
				$LIKE.=" BINARY";
		}
		if( $params["minus"] == 1 ) 
			$WHEREADD = 'and amount < 0';
		return $this->select_rows( "SELECT month(bookingdate) month, year(bookingdate) year, round(sum(amount),2) amount,comment FROM moneyflows WHERE $SEARCHCOL $LIKE '".$params["pattern"]."' and bookingdate between STR_TO_DATE('".$params['startdate']."',GET_FORMAT(DATE,'ISO')) and STR_TO_DATE('".$params['enddate']."',GET_FORMAT(DATE,'ISO')) $WHEREADD group by year(bookingdate),month(bookingdate) order by year,month" );
	}
}
