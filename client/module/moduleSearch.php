<?php
#-
# Copyright (c) 2006-2013 Oliver Lehmann <oliver@FreeBSD.org>
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
# $Id: moduleSearch.php,v 1.19 2013/07/27 23:06:48 olivleh1 Exp $
#

require_once 'module/module.php';
require_once 'core/coreContractPartners.php';
require_once 'core/coreCurrencies.php';
require_once 'core/coreMoneyFlows.php';
require_once 'core/coreSettings.php';

class moduleSearch extends module {

	function moduleSearch() {
		$this->module();
		$this->coreContractPartners = new coreContractPartners();
		$this->coreCurrencies       = new coreCurrencies();
		$this->coreMoneyFlows       = new coreMoneyFlows();
		$this->coreSettings         = new coreSettings();

		$date_format = $this->coreSettings->get_date_format( USERID );
		$this->date_format = $date_format['dateformat'];
	}

	function display_search() {

		$contractpartner_values = $this->coreContractPartners->get_all_names();
		$searchparams = $this->template->getTemplateVars( 'SEARCHPARAMS' );
		if( empty( $searchparams ) ) {
			$searchparams['grouping1'] = 'year';
			$searchparams['grouping2'] = 'month';
			$searchparams['order']     = 'grouping';
			$this->template->assign( 'SEARCHPARAMS', $searchparams );
		}
		foreach( $contractpartner_values as $key => $value ) {
			$contractpartner_values[$key]['name'] = htmlentities($value['name'], ENT_COMPAT | ENT_HTML401, ENCODING);
		}
		$this->template->assign( 'CONTRACTPARTNER_VALUES', $contractpartner_values );
		$this->template->assign( 'ERRORS',                 $this->get_errors() );

		$this->parse_header();
		return $this->fetch_template( 'display_search.tpl' );
	}

	function do_search( $searchstring, $contractpartner, $startdate, $enddate, $equal, $casesensitive, $regexp, $minus, $grouping1, $grouping2, $order ) {
		if( $equal )
			$searchparams['equal'] = 1;
		if( $casesensitive )
			$searchparams['casesensitive'] = 1;
		if( $regexp )
			$searchparams['regexp'] = 1;
		if( $orderbyseries )
			$searchparams['orderbyseries'] = 1;
		if( $minus )
			$searchparams['minus'] = 1;

		$valid_data = true;
		$startdate_orig = $startdate;
		$enddate_orig   = $enddate;

		if( !empty( $startdate ) ) {
			$startdate             = convert_date_to_db( $startdate, $this->date_format );
			if( $startdate === false ) {
				add_error( 147, array( $this->date_format ) );
				$startdate                       = $startdate_orig;
				$searchparams['startdate_error'] = 1;
				$valid_data = false;
			}
		}

		if( !empty( $enddate ) ) {
			$enddate             = convert_date_to_db( $enddate, $this->date_format );
			if( $enddate === false ) {
				add_error( 147, array( $this->date_format ) );
				$enddate                       = $startdate_orig;
				$searchparams['enddate_error'] = 1;
				$valid_data = false;
			}
		}

		$searchparams['mcp_contractpartnerid']   = $contractpartner;
		$searchparams['pattern']   = stripslashes( $searchstring );
		$searchparams['startdate'] = $startdate;
		$searchparams['enddate']   = $enddate;
		$searchparams['grouping1'] = $grouping1;
		$searchparams['grouping2'] = $grouping2;
		$searchparams['order']     = $order;

		if( empty( $searchparams['mcp_contractpartnerid'] ) && empty( $searchparams['pattern'] ) ) {
			add_error( 141 );
		} elseif ( empty( $searchparams['grouping1'] ) && empty( $searchparams['grouping2'] ) ) {
			add_error( 142 );
		} else {
			$results = $this->coreMoneyFlows->search_moneyflows( $searchparams );
			if( is_array( $results ) ) {
				$this->template->assign( 'SEARCH_DONE', 1 );
				foreach( array_keys( $results[0] ) as $column ) {
					$columns[$column] = 1;
				}
 				if($searchparams['grouping1'] == "contractpartner" || $searchparams['grouping2'] == "contractpartner") {
 					foreach($results as $key => $result) {
 						$result[$key]['name'] = htmlentities($value['name'], ENT_COMPAT | ENT_HTML401, ENCODING);
 					}
 				}  
			} else {
				add_error( 143 );
			}
		}
	    
		if( empty( $searchparams['startdate_error'] ) && !empty( $searchparams['startdate'] ) )
			$searchparams['startdate'] = convert_date_to_gui( $searchparams['startdate'], $this->date_format );
		if( empty( $searchparams['enddate_error'] ) && !empty( $searchparams['enddate'] ) )
			$searchparams['enddate'] = convert_date_to_gui( $searchparams['enddate'], $this->date_format );

		$this->template->assign( 'SEARCHPARAMS', $searchparams );
		$this->template->assign( 'COLUMNS',      $columns );
		$this->template->assign( 'RESULTS',      $results );
		$this->template->assign( 'CURRENCY',     $this->coreCurrencies->get_displayed_currency() );
		return $this->display_search();
	}
}
?>
