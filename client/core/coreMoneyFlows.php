<?php
#-
# Copyright (c) 2005-2014 Oliver Lehmann <oliver@FreeBSD.org>
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
# $Id: coreMoneyFlows.php,v 1.62 2014/02/09 14:19:03 olivleh1 Exp $
#

require_once 'core/core.php';

class coreMoneyFlows extends core {

	function coreMoneyFlows() {
		parent::__construct();
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
				$group['wkeyword']  = ' AND';
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
			$WHERE_KEYWORD    = ' AND';
		}

		if( $params['minus'] == 1 ) {
			$WHERE_CONDITION .= $WHERE_KEYWORD.' a.amount < 0 ';
			$WHERE_KEYWORD    = ' AND';
		}

		if( !empty( $params['mcp_contractpartnerid'] ) ) {
			$WHERE_CONDITION .=  $WHERE_KEYWORD.' a.mcp_contractpartnerid = '.$params['mcp_contractpartnerid'];
			$WHERE_KEYWORD    = ' AND';
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
						  FROM vw_moneyflows a
						       $JOIN_CONDITION
						 WHERE$WHERE_CONDITION
						   AND a.bookingdate BETWEEN ".$params['startdate']." AND ".$params['enddate']."
						   AND a.mug_mur_userid  = ".USERID."
						   AND (a.private        = 0
						        OR
						        a.mur_userid     = ".USERID."
						       )
						 GROUP BY$GROUP_CONDITION
						 ORDER BY$ORDER_CONDITION" );
	}

}
