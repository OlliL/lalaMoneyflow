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
# $Id: moduleMoneyFlows.php,v 1.40 2007/10/02 13:37:06 olivleh1 Exp $
#

require_once 'module/module.php';
require_once 'core/coreCapitalSources.php';
require_once 'core/coreContractPartners.php';
require_once 'core/coreCurrencies.php';
require_once 'core/coreMoneyFlows.php';
require_once 'core/corePreDefMoneyFlows.php';
require_once 'core/coreSettings.php';

class moduleMoneyFlows extends module {

	function moduleMoneyFlows() {
		$this->module();
		$this->coreCapitalSources   = new coreCapitalSources();
		$this->coreContractPartners = new coreContractPartners();
		$this->coreCurrencies       = new coreCurrencies();
		$this->coreMoneyFlows       = new coreMoneyFlows();
		$this->corePreDefMoneyFlows = new corePreDefMoneyFlows();
		$this->coreSettings         = new coreSettings();

		$date_format = $this->coreSettings->get_date_format( USERID );
		$this->date_format = $date_format['dateformat'];
	}

	function display_edit_moneyflow( $realaction, $id, $all_data ) {

		if( empty( $id ) )
			return;
			
		$bookingdate_orig = $all_data['bookingdate'];
		$invoicedate_orig = $all_data['invoicedate'];

		$checkdate = $all_data['bookingdate'];

		switch( $realaction ) {
			case 'save':
				$all_data['bookingdate'] = convert_date_to_db( $all_data['bookingdate'], $this->date_format );
				$all_data['invoicedate'] = convert_date_to_db( $all_data['invoicedate'], $this->date_format );
				$valid_data = true;

				if( $all_data['bookingdate'] === false ) {
					add_error( 147, array( $this->date_format ) );
					$all_data['bookingdate']       = $bookingdate_orig;
					$all_data['bookingdate_error'] = 1;
					$valid_data = false;
					$checkdate = $this->coreMoneyFlows->get_bookingdate( $id );
				}
				if( $all_data['invoicedate'] === false ) {
					add_error( 147, array( $this->date_format ) );
					$all_data['invoicedate']       = $invoicedate_orig;
					$all_data['invoicedate_error'] = 1;
					$valid_data = false;
				}

				if( $valid_data === true ) {
					$ret = $this->coreMoneyFlows->update_moneyflow( $id, $all_data['bookingdate'], $all_data['invoicedate'], $all_data['amount'], $all_data['mcs_capitalsourceid'], $all_data['mcp_contractpartnerid'], $all_data['comment'] );
					if( $ret === true ) {
						$this->template->assign( 'CLOSE', 1 );
						break;
					}
				}
			default:
				if( !is_array( $all_data ) ) {
					$all_data = $this->coreMoneyFlows->get_id_data( $id );
				}

				$capitalsourceid = $this->coreMoneyFlows->get_capitalsourceid( $id );

				if ( $this->coreCapitalSources->id_is_valid( $capitalsourceid, date( 'Y-m-d' ) ) ) {
					$capitalsource_values = $this->coreCapitalSources->get_valid_comments( $checkdate );
				} else {
					$capitalsource_values = $this->coreCapitalSources->get_all_comments();
				}

				$contractpartner_values = $this->coreContractPartners->get_all_names();

				$this->template->assign( 'CAPITALSOURCE_VALUES',   $capitalsource_values   );
				$this->template->assign( 'CONTRACTPARTNER_VALUES', $contractpartner_values );
				break;
		}

		if( empty( $all_data['bookingdate_error'] ) )
			$all_data['bookingdate'] = convert_date_to_gui( $all_data['bookingdate'], $this->date_format );
		if( empty( $all_data['invoicedate_error'] ) )
			$all_data['invoicedate'] = convert_date_to_gui( $all_data['invoicedate'], $this->date_format );

		$this->template->assign( 'ALL_DATA',    $all_data );
		$this->template->assign( 'MONEYFLOWID', $id       );
		$this->template->assign( 'CURRENCY',    $this->coreCurrencies->get_displayed_currency() );
		$this->template->assign( 'ERRORS',      $this->get_errors() );

		$this->parse_header( 1 );
		return $this->fetch_template( 'display_edit_moneyflow.tpl' );
	}


	function display_add_moneyflow( $realaction, $all_data ) {

		$capitalsource_values   = $this->coreCapitalSources->get_valid_comments();
		$contractpartner_values = $this->coreContractPartners->get_all_names();

		switch( $realaction ) {
			case 'save':
				$data_is_valid   = true;
				$nothing_checked = true;
				foreach( $all_data as $id => $value ) {
					if ( $value['checked'] == 1 ) {
						$bookingdate_orig = $value['bookingdate'];
						$invoicedate_orig = $value['invoicedate'];

						$nothing_checked = false;
						$all_data[$id]['bookingdate'] = convert_date_to_db( $value['bookingdate'], $this->date_format );
						
						if( empty( $value['mcs_capitalsourceid'] ) ) {
							add_error( 127 );
							$data_is_valid = false;
						}
						
						if( empty( $value['mcp_contractpartnerid'] ) ) {
							add_error( 128 );
							$data_is_valid = false;
						}

						if( ! empty( $value['invoicedate'] ) ) {
							$all_data[$id]['invoicedate'] = convert_date_to_db( $value['invoicedate'], $this->date_format );
							if( $all_data[$id]['invoicedate'] === false ) {
								add_error( 129, array( $this->date_format ) );
								$all_data[$id]['invoicedate']       = $invoicedate_orig;
								$all_data[$id]['invoicedate_error'] = 1;
							}
						}

						if( $all_data[$id]['bookingdate'] === false ) {
							add_error( 130, array( $this->date_format ) );
							$all_data[$id]['bookingdate']       = $bookingdate_orig;
							$all_data[$id]['bookingdate_error'] = 1;
							$data_is_valid = false;
						}

						if( ! $this->coreCapitalSources->id_is_valid( $value['mcs_capitalsourceid'], $all_data[$id]['bookingdate'] ) ) {
							add_error( 181 );
							$all_data[$id]['capitalsource_error'] = 1;
							$data_is_valid = false;
						}

						if( empty( $value['comment'] ) ) {
							add_error( 131 );
							$data_is_valid = false;
						}
							
						if( ! (    preg_match( '/^-{0,1}[0-9]*([\.][0-9][0-9][0-9]){0,}([,][0-9]{1,2}){0,1}$/', $value['amount'] ) 
						       ||  preg_match( '/^-{0,1}[0-9]*([,][0-9][0-9][0-9]){0,}([\.][0-9]{1,2}){0,1}$/', $value['amount'] )
						      ) ) {
							add_error( 132, array( $value['amount'] ) );
							$all_data[$id]['amount_error'] = 1;
							$data_is_valid = false;
						}
					}
				}
				
				if( $nothing_checked ) {
					add_error( 133 );
					$data_is_valid = false;
				}
				if( $data_is_valid ) {
					foreach( $all_data as $id => $value ) {
						if ( $value['checked'] == 1 ) {
							if( empty( $value['invoicedate'] ) )
								$value['invoicedate'] = $value['bookingdate'];
							$ret = $this->coreMoneyFlows->add_moneyflow( $value['bookingdate'], $value['invoicedate'], $value['amount'], $value['mcs_capitalsourceid'], $value['mcp_contractpartnerid'], $value['comment'] );
						}
					}
				} else {
					break;
				}
			default:
				$date = date( 'Y-m-d' );
				$all_data_pre = $this->corePreDefMoneyFlows->get_valid_data();

				$all_data[0] = array( 'id'          =>  -1,
				                    'bookingdate' => $date );

				if( is_array( $all_data_pre ) ) {
					$i = 1;				
					foreach( $all_data_pre as $key => $value ) {
						$all_data[$i] = $value;
						$all_data[$i]['bookingdate'] = $date;
						$all_data[$i]['amount'] = sprintf( '%.02f', $all_data_pre[$key]['amount'] );
						$all_data[$i]['capitalsourcecomment'] = $this->coreCapitalSources->get_comment( $all_data_pre[$key]['mcs_capitalsourceid'] );
						$all_data[$i]['contractpartnername']  = $this->coreContractPartners->get_name( $all_data_pre[$key]['mcp_contractpartnerid'] );
						$i++;
					}
				}

				break;
		}

		foreach( $all_data as $key => $value ) {
			if( empty( $all_data[$key]['bookingdate_error'] ) )
				$all_data[$key]['bookingdate'] = convert_date_to_gui( $all_data[$key]['bookingdate'], $this->date_format );
			if( empty( $all_data[$key]['invoicedate_error'] ) )
				$all_data[$key]['invoicedate'] = convert_date_to_gui( $all_data[$key]['invoicedate'], $this->date_format );
		}

		$this->template->assign( 'CAPITALSOURCE_VALUES',   $capitalsource_values   );
		$this->template->assign( 'CONTRACTPARTNER_VALUES', $contractpartner_values );
		$this->template->assign( 'ALL_DATA',               $all_data               );
		$this->template->assign( 'CURRENCY',               $this->coreCurrencies->get_displayed_currency() );
		$this->template->assign( 'ERRORS',                 $this->get_errors() );

		$this->parse_header();
		return $this->fetch_template( 'display_add_moneyflow.tpl' );
			
	}

	function display_delete_moneyflow( $realaction, $id ) {

		switch( $realaction ) {
			case 'yes':
				if( $this->coreMoneyFlows->delete_moneyflow( $id ) ) {
					$this->template->assign( 'CLOSE', 1 );
					break;
				}

			default:
				$all_data = $this->coreMoneyFlows->get_id_data( $id );
				$all_data['capitalsource_comment'] = $this->coreCapitalSources->get_comment( $all_data['mcs_capitalsourceid'] );
				$all_data['contractpartner_name']  = $this->coreContractPartners->get_name( $all_data['mcp_contractpartnerid'] );
				$all_data['bookingdate']           = convert_date_to_gui( $all_data['bookingdate'], $this->date_format );
				$all_data['invoicedate']           = convert_date_to_gui( $all_data['invoicedate'], $this->date_format );
				$this->template->assign( 'ALL_DATA', $all_data );
				break;
		}

		$this->template->assign( 'CURRENCY', $this->coreCurrencies->get_displayed_currency() );
		$this->template->assign( 'ERRORS',   $this->get_errors() );

		$this->parse_header( 1 );
		return $this->fetch_template( 'display_delete_moneyflow.tpl' );
	}
}
?>
