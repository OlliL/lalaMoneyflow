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
# $Id: coreMoneyFlows.php,v 1.26 2007/07/23 05:06:19 olivleh1 Exp $
#

require_once 'core/core.php';
require_once 'core/coreCapitalSources.php';

class coreMoneyFlows extends core {

	function coreMoneyFlows() {
		$this->core();
	}

	function get_all_data() {
		return $this->select_rows( "SELECT id
		                                  ,bookingdate
						  ,invoicedate
						  ,calc_amount(amount,'OUT',userid,invoicedate) amount
						  ,capitalsourceid
						  ,contractpartnerid
						  ,comment
					      FROM moneyflows
					     WHERE userid=".USERID."
					     ORDER BY id" );


	}

	function get_id_data( $id ) {
		return $this->select_row( "SELECT id
		                                 ,bookingdate
						 ,invoicedate
						 ,calc_amount(amount,'OUT',userid,invoicedate) amount
						 ,capitalsourceid
						 ,contractpartnerid
						 ,comment
					     FROM moneyflows
					    WHERE id=$id
					      AND userid=".USERID );
	}

	function get_all_monthly_data( $month, $year ) {
		$date = $this->make_date($year."-".$month."-01");
		return $this->select_rows( "SELECT id
		                                  ,bookingdate
						  ,invoicedate
						  ,calc_amount(amount,'OUT',userid,invoicedate) amount
						  ,capitalsourceid
						  ,contractpartnerid
						  ,comment
					      FROM moneyflows
					     WHERE bookingdate BETWEEN $date AND LAST_DAY($date)
					       AND userid=".USERID."
					     ORDER BY bookingdate
					             ,invoicedate" );
	}

	function get_all_monthly_joined_data( $month, $year, $sortby, $order ) {
		$date = $this->make_date($year."-".$month."-01");
		$sortbyadd=' mmf.bookingdate,mmf.invoicedate,mmf.id';
		switch( $sortby ) {
			case 'capitalsources_comment':	$sortby='capitalsourcecomment '.$order.',';
							break;
			case 'moneyflows_bookingdate':	$sortby='mmf.bookingdate '.$order.',';
							$sortbyadd=' mmf.invoicedate';
							break;
			case 'moneyflows_invoicedate':	$sortby='mmf.invoicedate '.$order.',';
							$sortbyadd=' mmf.bookingdate';
							break;
			case 'moneyflows_amount':	$sortby='4 '.$order.',';
							break;
			case 'moneyflows_comment':	$sortby='mmf.comment '.$order.',';
							break;
			case 'contractpartners_name':	$sortby='contractpartnername '.$order.',';
							break;
			default:			$sortby='';
		}
		return $this->select_rows( "SELECT mmf.id
		                                  ,mmf.bookingdate
						  ,mmf.invoicedate
						  ,calc_amount(mmf.amount,'OUT',mmf.userid,mmf.invoicedate) amount
						  ,mmf.comment
						  ,mcp.name contractpartnername
						  ,mcs.comment capitalsourcecomment
					      FROM moneyflows       mmf
					          ,contractpartners mcp
						  ,capitalsources   mcs
					     WHERE mmf.userid            = ".USERID."
					       AND mmf.userid            = mcp.userid
					       AND mmf.userid            = mcs.userid
					       AND mmf.contractpartnerid = mcp.id
					       AND mmf.capitalsourceid   = mcs.capitalsourceid
					       AND mmf.bookingdate         BETWEEN $date AND LAST_DAY($date)
					     ORDER BY $sortby $sortbyadd" );
	}

	function capitalsource_in_use( $id ) {
		if( $this->select_col( "SELECT COUNT(id)
		                          FROM moneyflows
					 WHERE capitalsourceid = $id
					   AND userid          = ".USERID ) > 0 )
			return 1;
		else
			return 0;
	}

	function capitalsource_in_use_out_of_date( $id, $validfrom, $validtil ) {
		if( $this->select_col( "SELECT COUNT(id)
		                          FROM moneyflows
					 WHERE capitalsourceid=$id
					  AND (
					        bookingdate < STR_TO_DATE('$validfrom',GET_FORMAT(DATE,'ISO'))
					       OR
					        bookingdate > STR_TO_DATE('$validtil', GET_FORMAT(DATE,'ISO'))
					      )
					  AND userid=".USERID ) > 0 )
			return 1;
		else
			return 0;
	}

	function contractpartner_in_use( $id ) {
		if( $this->select_col( "SELECT COUNT(id)
		                          FROM moneyflows
					 WHERE contractpartnerid = $id
					   AND userid            = ".USERID ) > 0 )
			return 1;
		else
			return 0;
	}

	function get_monthly_capitalsource_movement( $id, $month, $year ) {
		$start = $this->make_date($year."-".$month."-01");
		$end   = "LAST_DAY($start)";

		$movement=$this->select_col( "SELECT SUM(calc_amount(amount,'OUT',userid,invoicedate)) amount
		                                FROM moneyflows
					       WHERE userid=".USERID."
					         AND bookingdate       BETWEEN $start AND $end
						 AND capitalsourceid = $id" );
		if( empty( $movement ) )
			$movement=0;
		return $movement;
	}

	function get_year_capitalsource_movement( $month, $year ) {
		$start =  $this->make_date($year."-01-01");
		if( empty( $month ) ) {
			$end =  $this->make_date($year."-12-31");
		} else {
			$end   = 'LAST_DAY('.$this->make_date($year."-".$month."-01").')';
		}

		$movement=$this->select_col( "SELECT SUM(calc_amount(amount,'OUT',userid,invoicedate)) amount
		                                FROM moneyflows
					       WHERE userid=".USERID."
					         AND bookingdate BETWEEN $start AND $end" );
		if( empty( $movement ) )
			$movement=0;
		return $movement;
	}

	function get_all_years() {
		return $this->select_cols( 'SELECT DISTINCT YEAR(bookingdate) year
		                              FROM moneyflows
					     WHERE userid='.USERID.'
					     ORDER BY year ASC' );
	}

	function get_all_months( $year ) {
		return $this->select_cols( "SELECT DISTINCT MONTH(bookingdate) month
		                              FROM moneyflows
					     WHERE YEAR(bookingdate) = $year
					       AND userid=".USERID."
					     ORDER BY month ASC" );
	}


	function delete_moneyflow( $id ) {
		return $this->delete_row( "DELETE FROM moneyflows
		                            WHERE id=$id
					      AND userid=".USERID."
					    LIMIT 1" );
	}

	function get_capitalsourceid( $id ) {
		return $this->select_col( "SELECT capitalsourceid
		                             FROM moneyflows
					    WHERE id=$id
					      AND userid=".USERID );
	}

	function update_moneyflow( $id, $bookingdate, $invoicedate, $amount, $capitalsourceid, $contractpartnerid, $comment ) {
		$bookingdate = $this->make_date($bookingdate);
		$invoicedate = $this->make_date($invoicedate);
		$coreCapitalSources = new coreCapitalSources();
		if( $coreCapitalSources->id_is_valid( $capitalsourceid, $bookingdate ) ) {
			if( fix_amount( $amount ) ) {
				return $this->update_row( "UPDATE moneyflows
				                              SET bookingdate=$bookingdate
							         ,invoicedate=$invoicedate
								 ,amount=calc_amount('$amount','IN',".USERID.",$invoicedate)
								 ,capitalsourceid='$capitalsourceid'
								 ,contractpartnerid='$contractpartnerid'
								 ,comment='$comment'
							    WHERE id=$id
							      AND userid=".USERID );
			} else {
				return false;
			}
		} else {
			add_error( 4 );
			return false;
		}
	}

	function add_moneyflow( $bookingdate, $invoicedate, $amount, $capitalsourceid, $contractpartnerid, $comment ) {
		$bookingdate = $this->make_date($bookingdate);
		$invoicedate = $this->make_date($invoicedate);
		if (fix_amount( $amount )) {
			return $this->insert_row( "INSERT INTO moneyflows
			                                 (userid
							 ,bookingdate
							 ,invoicedate
							 ,amount
							 ,capitalsourceid
							 ,contractpartnerid
							 ,comment
							 )
							  VALUES
							 (".USERID."
							 ,$bookingdate
							 ,$invoicedate
							 ,calc_amount('$amount','IN',".USERID.",$invoicedate)
							 ,'$capitalsourceid'
							 ,'$contractpartnerid'
							 ,'$comment'
							 )" );
		} else {
			return false;
		}
	}

	function search_moneyflows( $params ) {
		$SEARCHCOL      = 'a.comment';
		$WHERE_KEYWORD  = 'WHERE';

		function create_group_by( $group ) {

			if( !empty( $group['by'] ) ) {
	
				switch( $group['by'] ) {
					case 'year':		$group['group']   .= $group['gkeyword'].' YEAR(a.bookingdate)';
								$group['order']   .= $group['okeyword'].' year';
								$group['select']  .= ', YEAR(a.bookingdate) year';
								break;
					case 'month':		$group['group']   .= $group['gkeyword'].' MONTH(a.bookingdate)';
								$group['order']   .= $group['okeyword'].' month';
								$group['select']  .= ', MONTH(a.bookingdate) month';
								break;
					case 'contractpartner':	$group['join']    .= $group['jkeyword'].' contractpartners b';
								$group['where']   .= $group['wkeyword'].' b.id = a.contractpartnerid';
								$group['group']   .= $group['gkeyword'].' b.name';
								$group['order']   .= $group['okeyword'].' b.name';
								$group['select']  .= ', b.name';
								break;
				}
				$group['gkeyword']  = ',';
				$group['okeyword']  = ',';
				$group['jkeyword']  = ',';
				$group['wkeyword']  = ',';
			}
			
			return( $group );
		}


		if( empty( $params['startdate'] ) )
			$params['startdate'] = '0000-00-00';
		if( empty( $params['enddate'] ) )
			$params['enddate'] = '9999-12-31';

		$params['startdate'] = $this->make_date( $params['startdate'] );
		$params['enddate']   = $this->make_date( $params['enddate'] );

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
			$WHERE_CONDITION .= $WHERE_KEYWORD.' a.amount < 0';
			$WHERE_KEYWORD    = 'AND';
		}

		if( !empty( $params['contractpartnerid'] ) ) {
			$WHERE_CONDITION .=  $WHERE_KEYWORD.' a.contractpartnerid = '.$params['contractpartnerid'];
			$WHERE_KEYWORD    = 'AND';
		}
		
		$group['gkeyword']  = 'GROUP BY';
		$group['okeyword']  = 'ORDER BY';
		$group['jkeyword']  = 'JOIN';
		$group['wkeyword']  = $WHERE_KEYWORD;
		$group['where']     = $WHERE_CONDITION;

		$group['by']      = $params['grouping1'];
		$group = create_group_by( $group );

		$group['by']      = $params['grouping2'];
		$group = create_group_by( $group );

		$GROUP_CONDITION  = $group['group'];
		$SELECT_CONDITION = $group['select'];
		$WHERE_CONDITION  = $group['where'];
		$JOIN_CONDITION   = $group['join'];

		if( $params['order'] == 'grouping' ) {
			$ORDER_CONDITION = $group['order'];
		} else {
			$ORDER_CONDITION = 'ORDER BY';
			switch( $params['order'] ) {
				case 'comment': $ORDER_CONDITION .= ' comment';
						break;
				case 'amount':
				default:	$ORDER_CONDITION .= ' amount';
						break;

			}
		}

		return $this->select_rows( "SELECT SUM(calc_amount(a.amount,'OUT',".USERID.",invoicedate)) amount
		                                  ,GROUP_CONCAT( DISTINCT a.comment ORDER BY comment DESC SEPARATOR ',') comment
					           $SELECT_CONDITION
					      FROM moneyflows a 
					           $JOIN_CONDITION
						   $WHERE_CONDITION 
					       AND a.bookingdate BETWEEN ".$params['startdate']." AND ".$params['enddate']."
					       AND a.userid=".USERID."
					           $GROUP_CONDITION
						   $ORDER_CONDITION" );
	}
}
