<?php
#-
# Copyright (c) 2006-2007 Oliver Lehmann <oliver@FreeBSD.org>
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
# $Id: moduleCurrencyRates.php,v 1.8 2007/07/28 20:26:28 olivleh1 Exp $
#

require_once 'module/module.php';
require_once 'core/coreCurrencyRates.php';
require_once 'core/coreCurrencies.php';
require_once 'core/coreSettings.php';

class moduleCurrencyRates extends module {

	function moduleCurrencyRates() {
		$this->module();
		$this->coreCurrencyRates = new coreCurrencyRates();
		$this->coreCurrencies    = new coreCurrencies();
		$this->coreSettings      = new coreSettings();

		$date_format = $this->coreSettings->get_date_format( USERID );
		$this->date_format = $date_format['dateformat'];
	}

	function display_list_currencyrates( $letter ) {

		$all_index_letters = $this->coreCurrencies->get_all_index_letters();
		$num_currencies    = $this->coreCurrencyRates->count_all_data();
		
		if( empty( $letter ) && $num_currencies < $this->coreTemplates->get_max_rows() ) {
			$letter = 'all';
		}
		
		if( $letter == 'all' ) {
			$all_data = $this->coreCurrencyRates->get_all_data();
		} elseif( !empty( $letter ) ) {
			$all_data = $this->coreCurrencyRates->get_all_matched_data( $letter );
		} else {
			$all_data = array();
		}

		foreach( $all_data as $key => $value ) {
			$all_data[$key]['validfrom'] = convert_date_to_gui( $all_data[$key]['validfrom'], $this->date_format );
			$all_data[$key]['validtil']  = convert_date_to_gui( $all_data[$key]['validtil'],  $this->date_format );
		}

		$this->template->assign( 'ALL_DATA',          $all_data          );
		$this->template->assign( 'COUNT_ALL_DATA',    count( $all_data ) );
		$this->template->assign( 'ALL_INDEX_LETTERS', $all_index_letters );

		$this->parse_header();
		return $this->fetch_template( 'display_list_currencyrates.tpl' );
	}

	function display_edit_currencyrate( $realaction, $currencyid, $validfrom, $all_data ) {

		$validfrom_orig = $all_data['validfrom'];

		switch( $realaction ) {
			case 'save':
				$validfrom             = convert_date_to_db( $validfrom, $this->date_format );
				$all_data['validfrom'] = convert_date_to_db( $all_data['validfrom'], $this->date_format );

				if( empty( $currencyid )  ) {
					$valid_data = true;

					if( $all_data['validfrom'] === false ) {
						add_error( 147, array($this->date_format) );
						$all_data['validfrom']       = $validfrom_orig;
						$all_data['validfrom_error'] = 1;
						$valid_data = false;
					} elseif( strtotime( $all_data['validfrom'] ) < time() ) {
						add_error( 148 );
						$valid = false;
					}
					
					if( empty( $all_data['rate'] ) ) {
						add_error( 149 );
						$valid = false;
					} elseif( !is_numeric( $all_data['rate'] ) ) {
						add_error( 150 );
						$valid = false;
					}

					if( $valid_data === true )
						$ret = $this->coreCurrencyRates->add_currencyrate( $all_data['mcu_currencyid'], $all_data['validfrom'], $all_data['rate'] );
				} else {
					$ret = $this->coreCurrencyRates->update_currencyrate( $currencyid, $validfrom, $all_data['rate'] );
				}

				if( $ret === true || $ret > 0 ) {
					$this->template->assign( 'CLOSE',    1 );
					break;
				}				
			default:
				$currency_values = $this->coreCurrencies->get_all_data();

				$this->template->assign( 'CURRENCY_VALUES',   $currency_values   );

				if( $currencyid > 0 && !empty( $validfrom ) && !is_array( $all_data ) ) {
					$all_data = $this->coreCurrencyRates->get_id_data( $currencyid, $validfrom );
				} else {
					$this->template->assign( 'NEW', 1 );
					if( empty( $all_data['validfrom'] ) ) 
						$all_data['validfrom'] = date( 'Y-m-d', time()+86400 );
				}
				break;
		}

		$validfrom             = convert_date_to_gui( $validfrom, $this->date_format );
		$all_data['validtil']  = convert_date_to_gui( $all_data['validtil'], $this->date_format );
		if( empty( $all_data['validfrom_error'] ) )
			$all_data['validfrom'] = convert_date_to_gui( $all_data['validfrom'], $this->date_format );

		$this->template->assign( 'ALL_DATA',   $all_data );
		$this->template->assign( 'CURRENCYID', $currencyid );
		$this->template->assign( 'VALIDFROM',  $validfrom );
		$this->template->assign( 'ERRORS',     $this->get_errors() );

		$this->parse_header( 1 );
		return $this->fetch_template( 'display_edit_currencyrates.tpl' );
	}
}
?>
