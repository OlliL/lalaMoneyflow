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
# $Id: moduleMoneyFlows.php,v 1.29 2007/07/22 10:59:17 olivleh1 Exp $
#

require_once 'module/module.php';
require_once 'core/coreCapitalSources.php';
require_once 'core/coreContractPartners.php';
require_once 'core/coreCurrencies.php';
require_once 'core/coreMoneyFlows.php';
require_once 'core/corePreDefMoneyFlows.php';

class moduleMoneyFlows extends module {

	function moduleMoneyFlows() {
		$this->module();
		$this->coreCapitalSources=new coreCapitalSources();
		$this->coreContractPartners=new coreContractPartners();
		$this->coreCurrencies=new coreCurrencies();
		$this->coreMoneyFlows=new coreMoneyFlows();
		$this->corePreDefMoneyFlows=new corePreDefMoneyFlows();
	}

	function display_edit_moneyflow( $realaction, $id, $all_data ) {

		switch( $realaction ) {
			case 'save':
				$ret=$this->coreMoneyFlows->update_moneyflow( $id, $all_data['bookingdate'], $all_data['invoicedate'], $all_data['amount'], $all_data['capitalsourceid'], $all_data['contractpartnerid'], $all_data['comment'] );

				if( $ret ) {
					$this->template->assign( 'CLOSE', 1 );
					break;
				}
			default:
				if( $id > 0 ) {
					$all_data=$this->coreMoneyFlows->get_id_data( $id );
					$this->template->assign( 'ALL_DATA', $all_data );
				}

				$capitalsourceid=$this->coreMoneyFlows->get_capitalsourceid( $id );

				if ( $this->coreCapitalSources->id_is_valid( $capitalsourceid, date( 'Y-m-d' ) ) ) {
					$capitalsource_values=$this->coreCapitalSources->get_valid_comments();
				} else {
					$capitalsource_values=$this->coreCapitalSources->get_all_comments();
				}

				$contractpartner_values=$this->coreContractPartners->get_all_names();

				$this->template->assign( 'CAPITALSOURCE_VALUES',   $capitalsource_values   );
				$this->template->assign( 'CONTRACTPARTNER_VALUES', $contractpartner_values );
				$this->template->assign( 'ERRORS',                 $this->get_errors() );
				break;
		}

		$this->template->assign( 'CURRENCY', $this->coreCurrencies->get_displayed_currency() );
		$this->template->assign( 'ERRORS',   $this->get_errors() );

		$this->parse_header( 1 );
		return $this->fetch_template( 'display_edit_moneyflow.tpl' );
	}


	function display_add_moneyflow( $realaction, $all_data ) {

		$date=date( 'Y-m-d' );
		$capitalsource_values=$this->coreCapitalSources->get_valid_comments();
		$contractpartner_values=$this->coreContractPartners->get_all_names();

		switch( $realaction ) {
			case 'save':
				$data_is_valid   = true;
				$nothing_checked = true;
				foreach( $all_data as $id => $value ) {
					if ( $value['checked'] == 1 ) {
						$nothing_checked = false;
						
						if( empty( $value['capitalsourceid'] ) ) {
							add_error( 9 );
							$data_is_valid = false;
						};
						
						if( empty( $value['contractpartnerid'] ) ) {
							add_error( 10 );
							$data_is_valid = false;
						};

						if( ! empty( $value['invoicedate'] ) && ! is_date( $value['invoicedate'] ) ) {
							add_error( 11 );
							$all_data[$id]['invoicedate_error'] = 1;
						}

						if( ! is_date( $value['bookingdate'] ) ) {
							add_error( 12 );
							$all_data[$id]['bookingdate_error'] = 1;
							$data_is_valid = false;
						}
	
						if( empty( $value['comment'] ) ) {
							add_error( 13 );
							$data_is_valid = false;
						}
							
						if( ! (    preg_match( '/^-{0,1}[0-9]*([\.][0-9][0-9][0-9]){0,}([,][0-9]{1,2}){0,1}$/', $value['amount'] ) 
						       ||  preg_match( '/^-{0,1}[0-9]*([,][0-9][0-9][0-9]){0,}([\.][0-9]{1,2}){0,1}$/', $value['amount'] )
						      ) ) {
							add_error( 14, array( $value['amount'] ) );
							$all_data[$id]['amount_error'] = 1;
							$data_is_valid = false;
						}
					}
				}
				
				if( $nothing_checked ) {
					add_error( 15 );
					$data_is_valid = false;
				}
				if( $data_is_valid ) {
					foreach( $all_data as $id => $value ) {
						if ( $value['checked'] == 1 ) {
							if( empty( $value['invoicedate'] ) )
								$value['invoicedate']=$value['bookingdate'];
							$ret=$this->coreMoneyFlows->add_moneyflow( $value['bookingdate'], $value['invoicedate'], $value['amount'], $value['capitalsourceid'], $value['contractpartnerid'], $value['comment'] );
						}
					}
				} else {
					break;
				}
			default:
				$all_data_pre=$this->corePreDefMoneyFlows->get_valid_data( $date, $date );

				$all_data[0]=array( 'id'          =>  -1,
				                    'bookingdate' => date( 'Y-m-d' ) );

				if( is_array( $all_data_pre ) ) {
					$i=1;				
					foreach( $all_data_pre as $key => $value ) {
						$all_data[$i]=$value;
						$all_data[$i]['bookingdate']=$date;
						$all_data[$i]['amount']=sprintf('%.02f',$all_data_pre[$key]['amount']);
						$all_data[$i]['capitalsourcecomment']=$this->coreCapitalSources->get_comment( $all_data_pre[$key]['capitalsourceid'] );
						$all_data[$i]['contractpartnername']=$this->coreContractPartners->get_name( $all_data_pre[$key]['contractpartnerid'] );
						$i++;
					}
				}

				break;
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
				$all_data=$this->coreMoneyFlows->get_id_data( $id );
				$all_data['capitalsource_comment']=$this->coreCapitalSources->get_comment( $all_data['capitalsourceid'] );
				$all_data['contractpartner_name']=$this->coreContractPartners->get_name( $all_data['contractpartnerid'] );
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
