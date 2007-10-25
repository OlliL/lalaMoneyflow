<?php
#-
# Copyright (c) 2005-2007 Oliver Lehmann <oliver@FreeBSD.org>
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
# $Id: coreMoneyFlows.php,v 1.35 2007/10/25 12:58:08 olivleh1 Exp $
#

require_once 'core/core.php';
require_once 'core/coreCapitalSources.php';

class coreMoneyFlows extends core {

	function coreMoneyFlows() {
		$this->core();
	}

	function get_all_data() {
		return $this->select_rows( "	SELECT moneyflowid
						      ,bookingdate
						      ,invoicedate
						      ,calc_amount(amount,'OUT',mur_userid,invoicedate) amount
						      ,mcs_capitalsourceid
						      ,mcp_contractpartnerid
						      ,comment
						  FROM moneyflows
						 WHERE mur_userid = ".USERID."
						 ORDER BY moneyflowid" );


	}

	function get_id_data( $id ) {
		return $this->select_row( "	SELECT moneyflowid
						      ,bookingdate
						      ,invoicedate
						      ,calc_amount(amount,'OUT',mur_userid,invoicedate) amount
						      ,mcs_capitalsourceid
						      ,mcp_contractpartnerid
						      ,comment
						  FROM moneyflows
						 WHERE moneyflowid = $id
						   AND mur_userid  = ".USERID );
	}

	function get_all_monthly_data( $month, $year ) {
		$date = $this->make_date( $year."-".$month."-01" );
		return $this->select_rows( "	SELECT moneyflowid
						      ,bookingdate
						      ,invoicedate
						      ,calc_amount(amount,'OUT',mur_userid,invoicedate) amount
						      ,mcs_capitalsourceid
						      ,mcp_contractpartnerid
						      ,comment
						  FROM moneyflows
						 WHERE bookingdate  BETWEEN $date AND LAST_DAY($date)
						   AND mur_userid = ".USERID."
						 ORDER BY bookingdate
							 ,invoicedate" );
	}

	function get_all_date_source_data( $capitalsourceid, $startdate, $endate ) {
		$startdate = $this->make_date( $startdate );
		$enddate   = $this->make_date( $enddate );
		
		return $this->select_rows( "	SELECT moneyflowid
						      ,bookingdate
						      ,invoicedate
						      ,calc_amount(amount,'OUT',mur_userid,invoicedate) amount
						      ,mcs_capitalsourceid
						      ,mcp_contractpartnerid
						      ,comment
						  FROM moneyflows
						 WHERE bookingdate           BETWEEN $startdate AND $enddate
						   AND mcs_capitalsourceid = $capitalsourceid
						   AND mur_userid          = ".USERID."
						 ORDER BY bookingdate
							 ,invoicedate" );
	}

	function get_all_monthly_joined_data( $month, $year, $sortby, $order ) {
		$date = $this->make_date( $year."-".$month."-01" );
		$sortbyadd=' mmf.bookingdate,mmf.invoicedate,mmf.moneyflowid';
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
		return $this->select_rows( "	SELECT mmf.moneyflowid
						      ,mmf.bookingdate
						      ,mmf.invoicedate
						      ,calc_amount(mmf.amount,'OUT',mmf.mur_userid,mmf.invoicedate) amount
						      ,mmf.comment
						      ,mcp.name    contractpartnername
						      ,mcs.comment capitalsourcecomment
						  FROM moneyflows       mmf
						      ,contractpartners mcp
						      ,capitalsources   mcs
						 WHERE mmf.mur_userid            = ".USERID."
						   AND mmf.mur_userid            = mcp.mur_userid
						   AND mmf.mur_userid            = mcs.mur_userid
						   AND mmf.mcp_contractpartnerid = mcp.contractpartnerid
						   AND mmf.mcs_capitalsourceid   = mcs.capitalsourceid
						   AND mmf.bookingdate             BETWEEN $date AND LAST_DAY($date)
						 ORDER BY $sortby $sortbyadd" );
	}

	function capitalsource_in_use( $id ) {
		if( $this->select_col( "SELECT COUNT(moneyflowid)
					  FROM moneyflows
					 WHERE mcs_capitalsourceid = $id
					   AND mur_userid          = ".USERID ) > 0 )
			return 1;
		else
			return 0;
	}

	function capitalsource_in_use_out_of_date( $id, $validfrom, $validtil ) {
		if( $this->select_col( "SELECT COUNT(moneyflowid)
					  FROM moneyflows
					 WHERE mcs_capitalsourceid = $id
					  AND (
						bookingdate < STR_TO_DATE('$validfrom',GET_FORMAT(DATE,'ISO'))
					       OR
						bookingdate > STR_TO_DATE('$validtil', GET_FORMAT(DATE,'ISO'))
					      )
					  AND mur_userid=".USERID ) > 0 )
			return 1;
		else
			return 0;
	}

	function contractpartner_in_use( $id ) {
		if( $this->select_col( "SELECT COUNT(moneyflowid)
					  FROM moneyflows
					 WHERE mcp_contractpartnerid = $id
					   AND mur_userid            = ".USERID ) > 0 )
			return 1;
		else
			return 0;
	}

	function get_monthly_capitalsource_movement( $id, $month, $year ) {
		$start = $this->make_date( $year."-".$month."-01" );
		$end   = "LAST_DAY($start)";

		$movement=$this->select_col( "	SELECT SUM(calc_amount(amount,'OUT',mur_userid,invoicedate)) amount
						  FROM moneyflows
						 WHERE mur_userid          = ".USERID."
						   AND bookingdate           BETWEEN $start AND $end
						   AND mcs_capitalsourceid = $id" );
		if( empty( $movement ) )
			$movement=0;
		return $movement;
	}

	function get_range_movement( $startmonth, $endmonth, $year ) {
		$start = $this->make_date($year.'-'.$startmonth.'-01');
		$end   = 'LAST_DAY('.$this->make_date($year.'-'.$endmonth.'-01').')';

		$movement = $this->select_col( "SELECT SUM(calc_amount(amount,'OUT',mur_userid,invoicedate)) amount
						  FROM moneyflows
						 WHERE mur_userid = ".USERID."
						   AND bookingdate  BETWEEN $start AND $end" );
		if( empty( $movement ) )
			$movement=0;
		return $movement;
	}

	function get_all_years() {
		return $this->select_cols( '	SELECT DISTINCT YEAR(bookingdate) year
						  FROM moneyflows
						 WHERE mur_userid = '.USERID.'
						 ORDER BY year ASC' );
	}

	function get_all_months( $year ) {
		return $this->select_cols( "	SELECT DISTINCT MONTH(bookingdate) month
						  FROM moneyflows
						 WHERE YEAR(bookingdate) = $year
						   AND mur_userid        = ".USERID."
						 ORDER BY month ASC" );
	}

	function get_max_year_month() {
		return $this->select_row( '	SELECT MONTH(bookingdate) month
						      ,YEAR(bookingdate) year
						  FROM moneyflows
						 WHERE mur_userid  = '.USERID.'
						   AND bookingdate = (SELECT MAX(bookingdate)
						                        FROM moneyflows
						                       WHERE mur_userid = '.USERID.'
						                       LIMIT 1)
						 LIMIT 1');
	}

	function delete_moneyflow( $id ) {
		return $this->delete_row( "	DELETE FROM moneyflows
						 WHERE moneyflowid = $id
						   AND mur_userid  = ".USERID."
						 LIMIT 1" );
	}

	function get_capitalsourceid( $id ) {
		return $this->select_col( "	SELECT mcs_capitalsourceid
						  FROM moneyflows
						 WHERE moneyflowid = $id
						   AND mur_userid  = ".USERID );
	}

	function get_bookingdate( $id ) {
		return $this->select_col( "	SELECT bookingdate
						  FROM moneyflows
						 WHERE moneyflowid = $id
						   AND mur_userid  = ".USERID );
	}

	function update_moneyflow( $id, $bookingdate, $invoicedate, $amount, $capitalsourceid, $contractpartnerid, $comment ) {
		$coreCapitalSources = new coreCapitalSources();
		if( $coreCapitalSources->id_is_valid( $capitalsourceid, $bookingdate ) ) {
			$bookingdate = $this->make_date( $bookingdate );
			$invoicedate = $this->make_date( $invoicedate );
			if( fix_amount( $amount ) ) {
				return $this->update_row( "	UPDATE moneyflows
								   SET bookingdate           = $bookingdate
								      ,invoicedate           = $invoicedate
								      ,amount                = calc_amount('$amount','IN',".USERID.",$invoicedate)
								      ,mcs_capitalsourceid   = '$capitalsourceid'
								      ,mcp_contractpartnerid = '$contractpartnerid'
								      ,comment               = '$comment'
								 WHERE moneyflowid = $id
								   AND mur_userid  = ".USERID );
			} else {
				return false;
			}
		} else {
			add_error( 122 );
			return false;
		}
	}

	function add_moneyflow( $bookingdate, $invoicedate, $amount, $capitalsourceid, $contractpartnerid, $comment ) {
		$bookingdate = $this->make_date( $bookingdate );
		$invoicedate = $this->make_date( $invoicedate );
		if (fix_amount( $amount )) {
			return $this->insert_row( "	INSERT INTO moneyflows
							      (mur_userid
							      ,bookingdate
							      ,invoicedate
							      ,amount
							      ,mcs_capitalsourceid
							      ,mcp_contractpartnerid
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
		$WHERE_KEYWORD  = '';

		function create_group_by( $group ) {

			if( !empty( $group['by'] ) ) {
	
				switch( $group['by'] ) {
					case 'year':		$group['group']   .= $group['gkeyword'].' YEAR(a.bookingdate)';
								$group['order']   .= $group['okeyword'].' year';
								$group['select']  .= ',YEAR(a.bookingdate) year';
								break;
					case 'month':		$group['group']   .= $group['gkeyword'].' MONTH(a.bookingdate)';
								$group['order']   .= $group['okeyword'].' month';
								$group['select']  .= ',MONTH(a.bookingdate) month';
								break;
					case 'contractpartner':	$group['join']    .= $group['jkeyword'].' contractpartners b';
								$group['where']   .= $group['wkeyword'].' b.contractpartnerid = a.mcp_contractpartnerid ';
								$group['group']   .= $group['gkeyword'].' b.name';
								$group['order']   .= $group['okeyword'].' b.name';
								$group['select']  .= ', b.name';
								break;
				}
				$group['gkeyword']  = ',';
				$group['okeyword']  = ',';
				$group['jkeyword']  = ',';
				$group['wkeyword']  = 'AND';
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
					$LIKE              = "REGEXP";
					$params['pattern'] = str_replace('\]','\\\]',$params['pattern']);
					$params['pattern'] = str_replace('\[','\\\[',$params['pattern']);
				}
				else {
					$LIKE    = 'LIKE';
					$params['pattern'] = '%'.$params['pattern'].'%';
				}
				if( $params['casesensitive'] == 1 )
					$LIKE   .=' BINARY';
			}
			$WHERE_CONDITION  = $WHERE_KEYWORD.' '.$SEARCHCOL.' '.$LIKE." '".$params["pattern"]."' ";
			$WHERE_KEYWORD    = 'AND';
		}
		
		if( $params['minus'] == 1 ) {
			$WHERE_CONDITION .= $WHERE_KEYWORD.' a.amount < 0 ';
			$WHERE_KEYWORD    = 'AND';
		}

		if( !empty( $params['mcp_contractpartnerid'] ) ) {
			$WHERE_CONDITION .=  $WHERE_KEYWORD.' a.mcp_contractpartnerid = '.$params['mcp_contractpartnerid'];
			$WHERE_KEYWORD    = 'AND';
		}
		
		$group['gkeyword']  = '';
		$group['okeyword']  = '';
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
			switch( $params['order'] ) {
				case 'comment': $ORDER_CONDITION = ' comment';
						break;
				case 'amount':
				default:	$ORDER_CONDITION = ' amount';
						break;

			}
		}

		return $this->select_rows( "	SELECT SUM(calc_amount(a.amount,'OUT',".USERID.",invoicedate))               amount
						      ,GROUP_CONCAT( DISTINCT a.comment ORDER BY comment DESC SEPARATOR ',') comment
						      $SELECT_CONDITION
						  FROM moneyflows a 
						       $JOIN_CONDITION
						 WHERE$WHERE_CONDITION 
						   AND a.bookingdate BETWEEN ".$params['startdate']." AND ".$params['enddate']."
						   AND a.mur_userid  =".USERID."
						 GROUP BY$GROUP_CONDITION
						 ORDER BY$ORDER_CONDITION" );
	}

	function find_single_moneyflow( $date, $date_days_around, $amount ) {
		$date = $this->make_date( $date );
		return $this->select_cols( "	SELECT moneyflowid
						  FROM moneyflows
						 WHERE bookingdate BETWEEN DATE_SUB($date, INTERVAL $date_days_around DAY) AND DATE_ADD($date, INTERVAL $date_days_around DAY)
						   AND amount     = $amount
						   AND mur_userid = ".USERID );
	}
}
