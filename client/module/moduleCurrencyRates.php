<?php
#-
# Copyright (c) 2006 Oliver Lehmann <oliver@FreeBSD.org>
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
# $Id: moduleCurrencyRates.php,v 1.3 2007/07/24 18:34:31 olivleh1 Exp $
#

require_once 'module/module.php';
require_once 'core/coreCurrencyRates.php';
require_once 'core/coreCurrencies.php';

class moduleCurrencyRates extends module {

	function moduleCurrencyRates() {
		$this->module();
		$this->coreCurrencyRates = new coreCurrencyRates();
		$this->coreCurrencies    = new coreCurrencies();
	}

	function display_list_currencyrates( $letter ) {

		$all_index_letters = $this->coreCurrencies->get_all_index_letters();
		$num_currencies = $this->coreCurrencyRates->count_all_data();
		
		if( empty($letter) && $num_currencies < MAX_ROWS ) {
			$letter = 'all';
		}
		
		if( $letter == 'all') {
			$all_data = $this->coreCurrencyRates->get_all_data();
		} elseif( !empty( $letter ) ) {
			$all_data = $this->coreCurrencyRates->get_all_matched_data( $letter );
		} else {
			$all_data=array();
		}

		$this->template->assign( 'ALL_DATA',          $all_data          );
		$this->template->assign( 'COUNT_ALL_DATA',    count( $all_data ) );
		$this->template->assign( 'ALL_INDEX_LETTERS', $all_index_letters );

		$this->parse_header();
		return $this->fetch_template( 'display_list_currencyrates.tpl' );
	}

	function display_edit_currencyrate( $realaction, $currencyid, $validfrom, $all_data ) {

		switch( $realaction ) {
			case 'save':
				if( $currencyid == 0 ) {
					$valid = true;
					if( ! is_date( $all_data['validfrom'] ) ) {
						add_error( 29 );
						$valid = false;
					} elseif( strtotime( $all_data['validfrom'] ) < time() ) {
						add_error( 30 );
						$valid = false;
					}
					
					if( empty( $all_data['rate'] ) ) {
						add_error( 31 );
						$valid = false;
					} elseif( !is_numeric( $all_data['rate'] ) ) {
						add_error( 32 );
						$valid = false;
					}
					if( $valid )			
						$ret = $this->coreCurrencyRates->add_currencyrate( $all_data['mcu_currencyid'], $all_data['validfrom'], $all_data['rate'] );
				} else {
					$ret = $this->coreCurrencyRates->update_currencyrate( $currencyid, $validfrom, $all_data['rate'] );
				}

				if( $ret ) {
					$this->template->assign( 'CLOSE',    1 );
					break;
				}				
			default:
				$currency_values=$this->coreCurrencies->get_all_data();

				$this->template->assign( 'CURRENCY_VALUES',   $currency_values   );
				if( $currencyid > 0 && !empty( $validfrom ) ) {
					$all_data=$this->coreCurrencyRates->get_id_data( $currencyid, $validfrom );
				} else {
					$this->template->assign( 'NEW', 1 );
					if( empty( $all_data['validfrom'] ) ) 
						$all_data['validfrom'] = date( 'Y-m-d', time()+86400 );
				}
				$this->template->assign( 'ALL_DATA', $all_data );
				break;
		}

		$this->template->assign( 'CURRENCYID', $currencyid );
		$this->template->assign( 'VALIDFROM',  $validfrom );
		$this->template->assign( 'ERRORS',     $this->get_errors() );

		$this->parse_header( 1 );
		return $this->fetch_template( 'display_edit_currencyrates.tpl' );
	}
}
?>
