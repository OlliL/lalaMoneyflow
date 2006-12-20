<?php
#-
# Copyright (c) 2005-2006 Oliver Lehmann <oliver@FreeBSD.org>
# All rights reserved.
#
# Redistribution and use in source and binary forms, with or without
# modification, are permitted provided that the following conditions
# are met:
# 1. Redistributions of source code must retain the above copyright
#	notice, this list of conditions and the following disclaimer
# 2. Redistributions in binary form must reproduce the above copyright
#	notice, this list of conditions and the following disclaimer in the
#	documentation and/or other materials provided with the distribution.
#
# THIS SOFTWARE IS PROVIDED BY THE AUTHOR AND CONTRIBUTORS ``AS IS'' AND
# ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
# IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
# ARE DISCLAIMED.  IN NO EVENT SHALL THE AUTHOR OR CONTRIBUTORS BE LIABLE
# FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
# DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS
# OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION)
# HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
# LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY
# OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF
# SUCH DAMAGE.
#
# $Id: coreMoneyFlows.php,v 1.22 2006/12/20 17:45:06 olivleh1 Exp $
#

require_once 'core/core.php';
require_once 'core/coreCapitalSources.php';

class coreMoneyFlows extends core {

	function coreMoneyFlows() {
		$this->core();
	}

	function get_all_data( $id=0 ) {
		if( $id>0 )
			return $this->select_row( "SELECT id,bookingdate,invoicedate,calc_amount(amount,'OUT',userid) amount,capitalsourceid,contractpartnerid,comment FROM moneyflows WHERE id=$id AND userid=".USERID );
		else
			return $this->select_rows( "SELECT id,bookingdate,invoicedate,calc_amount(amount,'OUT',userid) amount,capitalsourceid,contractpartnerid,comment FROM moneyflows WHERE userid=".USERID." ORDER BY id" );


	}

	function get_id_data( $id ) {
		return $this->select_row( "SELECT id,bookingdate,invoicedate,calc_amount(amount,'OUT',userid) amount,capitalsourceid,contractpartnerid,comment FROM moneyflows WHERE id=$id AND userid=".USERID );
	}

	function get_all_monthly_data( $month, $year ) {
		return $this->select_rows( "SELECT id,bookingdate,invoicedate,calc_amount(amount,'OUT',userid) amount,capitalsourceid,contractpartnerid,comment FROM moneyflows WHERE bookingdate BETWEEN '$year-$month-01' AND DATE_ADD('$year-$month-01', INTERVAL 1 MONTH) AND userid=".USERID." ORDER BY bookingdate,invoicedate" );
	}

	function get_all_monthly_joined_data( $month, $year, $sortby, $order ) {
		$sortbyadd=' bookingdate,invoicedate,id';
		switch( $sortby ) {
			case 'capitalsources_comment':	$sortby='capitalsourcecomment '.$order.',';
							break;
			case 'moneyflows_bookingdate':	$sortby='a.bookingdate '.$order.',';
							$sortbyadd=' invoicedate';
							break;
			case 'moneyflows_invoicedate':	$sortby='a.invoicedate '.$order.',';
							$sortbyadd=' bookingdate';
							break;
			case 'moneyflows_amount':	$sortby='4 '.$order.',';
							break;
			case 'moneyflows_comment':	$sortby='a.comment '.$order.',';
							break;
			case 'contractpartners_name':	$sortby='contractpartnername '.$order.',';
							break;
			default:			$sortby='';
		}
		return $this->select_rows( "SELECT a.id,a.bookingdate,a.invoicedate,calc_amount(a.amount,'OUT',a.userid) amount,a.comment,b.name contractpartnername,c.comment capitalsourcecomment FROM moneyflows a, contractpartners b, capitalsources c WHERE a.userid=".USERID." AND b.userid=a.userid AND c.userid=a.userid AND a.bookingdate >= '$year-$month-01' AND a.bookingdate < DATE_ADD('$year-$month-01', INTERVAL 1 MONTH) AND a.contractpartnerid=b.id AND a.capitalsourceid=c.id ORDER BY $sortby $sortbyadd" );
	}

	function capitalsource_in_use( $id ) {
		if( $this->select_col( "SELECT COUNT(id) FROM moneyflows WHERE capitalsourceid=$id AND userid=".USERID ) > 0 )
			return 1;
		else
			return 0;
	}

	function capitalsource_in_use_out_of_date( $id, $validfrom, $validtil ) {
		if( $this->select_col( "SELECT COUNT(id) FROM moneyflows WHERE capitalsourceid=$id AND ( bookingdate < '$validfrom' OR bookingdate > '$validtil') AND userid=".USERID ) > 0 )
			return 1;
		else
			return 0;
	}

	function contractpartner_in_use( $id ) {
		if( $this->select_col( "SELECT COUNT(id) FROM moneyflows WHERE contractpartnerid=$id AND userid=".USERID ) > 0 )
			return 1;
		else
			return 0;
	}

	function get_monthly_capitalsource_movement( $id, $month, $year ) {
		$start = "'".$year."-".$month."-01'";
		$end   = "DATE_ADD('$year-$month-01', INTERVAL 1 MONTH)";
		$where = " AND capitalsourceid=$id";

		$movement=$this->select_col( "SELECT calc_amount(sum(amount),'OUT',userid) amount FROM moneyflows WHERE userid=".USERID." AND bookingdate >= $start AND bookingdate < $end".$where );
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

		$movement=$this->select_col( "SELECT calc_amount(sum(amount),'OUT',userid) FROM moneyflows WHERE userid=".USERID." AND bookingdate >= $start AND bookingdate < $end" );
		if( empty( $movement ) )
			$movement=0;
		return $movement;
	}

	function get_all_years() {
		return $this->select_cols( 'SELECT DISTINCT YEAR(bookingdate) year FROM moneyflows WHERE userid='.USERID.' ORDER BY year ASC' );
	}

	function get_all_months( $year ) {
		return $this->select_cols( "SELECT DISTINCT MONTH(bookingdate) month FROM moneyflows WHERE YEAR(bookingdate) = $year AND userid=".USERID." ORDER BY month ASC" );
	}


	function delete_moneyflow( $id ) {
		return $this->delete_row( "DELETE FROM moneyflows WHERE id=$id AND userid=".USERID." LIMIT 1" );
	}

	function get_capitalsourceid( $id ) {
		return $this->select_col( "SELECT capitalsourceid FROM moneyflows WHERE id=$id AND userid=".USERID );
	}

	function update_moneyflow( $id, $bookingdate, $invoicedate, $amount, $capitalsourceid, $contractpartnerid, $comment ) {
		$coreCapitalSources = new coreCapitalSources();
		if( $coreCapitalSources->id_is_valid( $capitalsourceid, $bookingdate ) ) {
			if( fix_amount( $amount ) ) {
				return $this->update_row( "UPDATE moneyflows set bookingdate=STR_TO_DATE('$bookingdate',GET_FORMAT(DATE,'ISO')),invoicedate=STR_TO_DATE('$invoicedate',GET_FORMAT(DATE,'ISO')),amount=calc_amount('$amount','IN',".USERID."),capitalsourceid='$capitalsourceid',contractpartnerid='$contractpartnerid',comment='$comment' WHERE id=$id AND userid=".USERID );
			} else {
				return false;
			}
		} else {
			add_error( 4 );
			return false;
		}
	}

	function add_moneyflow( $bookingdate, $invoicedate, $amount, $capitalsourceid, $contractpartnerid, $comment ) {
		if (fix_amount( $amount )) {
			return $this->insert_row( "INSERT INTO moneyflows (userid,bookingdate,invoicedate,amount,capitalsourceid,contractpartnerid,comment) VALUES (".USERID.",STR_TO_DATE('$bookingdate',GET_FORMAT(DATE,'ISO')),STR_TO_DATE('$invoicedate',GET_FORMAT(DATE,'ISO')),calc_amount('$amount','IN',".USERID."),'$capitalsourceid','$contractpartnerid','$comment')" );
		} else {
			return false;
		}
	}

	function search_moneyflows( $params ) {
		$SEARCHCOL       = 'comment';
		$WHERE_KEYWORD   = 'WHERE';
		if( empty( $params['startdate'] ) )
			$params['startdate'] = '0000-00-00';
		if( empty( $params['enddate'] ) )
			$params['enddate'] = '9999-12-31';


		if( !empty( $params['pattern'] ) ) {
			if( $params['equal'] == 1 ) {
				$LIKE="=";
			} else {
				if( $params['regexp'] == 1 ) {
					$LIKE="REGEXP";
					$params['pattern'] = str_replace('\]','\\\]',$params['pattern']);
					$params['pattern'] = str_replace('\[','\\\[',$params['pattern']);
				}
				else
					$LIKE='LIKE';
				if( $params['casesensitive'] == 1 )
					$LIKE.=' BINARY';
			}
			$WHERE_CONDITION  = $WHERE_KEYWORD.' '.$SEARCHCOL.' '.$LIKE." '".$params["pattern"]."'";
			$WHERE_KEYWORD    = 'AND';
		}
		
		if( $params['minus'] == 1 ) {
			$WHERE_CONDITION .= $WHERE_KEYWORD.' amount < 0';
			$WHERE_KEYWORD    = 'AND';
		}

		if( !empty( $params['contractpartnerid'] ) ) {
			$WHERE_CONDITION .=  $WHERE_KEYWORD.' contractpartnerid = '.$params['contractpartnerid'];
			$WHERE_KEYWORD    = 'AND';
		}

		return $this->select_rows( "SELECT MONTH(bookingdate) month, YEAR(bookingdate) year, calc_amount(sum(amount),'OUT',".USERID.") amount,comment FROM moneyflows $WHERE_CONDITION AND bookingdate BETWEEN STR_TO_DATE('".$params['startdate']."',GET_FORMAT(DATE,'ISO')) AND STR_TO_DATE('".$params['enddate']."',GET_FORMAT(DATE,'ISO')) AND userid=".USERID." $WHEREADD GROUP BY YEAR(bookingdate),MONTH(bookingdate) ORDER BY year,month" );
	}
}
