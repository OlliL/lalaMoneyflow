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
# $Id: moduleMonthlySettlement.php,v 1.18 2007/07/24 18:22:09 olivleh1 Exp $
#

require_once 'module/module.php';
require_once 'core/coreCapitalSources.php';
require_once 'core/coreCurrencies.php';
require_once 'core/coreMonthlySettlement.php';
require_once 'core/coreText.php';

class moduleMonthlySettlement extends module {

	function moduleMonthlySettlement() {
		$this->module();
		$this->coreCapitalSources=new coreCapitalSources();
		$this->coreCurrencies=new coreCurrencies();
		$this->coreMoneyFlows=new coreMoneyFlows();
		$this->coreMonthlySettlement=new coreMonthlySettlement();
		$this->coreText=new coreText();
	}

	function display_list_monthlysettlements( $month, $year ) {

		if( !$year )
			$year=date( 'Y' );

		$years = $this->coreMonthlySettlement->get_all_years();
		$temp_months = $this->coreMonthlySettlement->get_all_months( $year );
		if( is_array( $temp_months ) ) {
			foreach( $temp_months as $key => $value ) {
				$months[] = array(
					'nummeric' => sprintf( '%02d', $value ),
					'name'     => $this->coreText->get_month( $value )
				);
			}
		}

		if( $month > 0 && $year > 0 ) {
			$all_ids=$this->coreCapitalSources->get_valid_ids();
			foreach( $all_ids as $id ) {
				$all_data[]=array(
					'id'      => $id,
					'comment' => $this->coreCapitalSources->get_comment( $id ),
					'amount'  => $this->coreMonthlySettlement->get_amount( $id, $month, $year )
				);
			}

			$sumamount=$this->coreMonthlySettlement->get_sum_amount( $month, $year );

			$month = array(
				'nummeric' => sprintf( '%02d', $month ),
				'name'     => $this->coreText->get_month( $month )
			);

			$this->template->assign( 'SUMAMOUNT',      $sumamount         );
			$this->template->assign( 'MONTH',          $month             );
			$this->template->assign( 'YEAR' ,          $year              );
			$this->template->assign( 'ALL_DATA',       $all_data          );
			$this->template->assign( 'COUNT_ALL_DATA', count( $all_data ) );
		}

		$this->template->assign( 'ALL_YEARS',     $years  );
		$this->template->assign( 'ALL_MONTHS',    $months );
		$this->template->assign( 'SELECTED_YEAR', $year   );
		$this->template->assign( 'CURRENCY',      $this->coreCurrencies->get_displayed_currency() );

		$this->parse_header();
		return $this->fetch_template( 'display_list_monthlysettlements.tpl' );
	}

	function display_edit_monthlysettlement( $realaction, $month, $year, $all_data ) {

		switch( $realaction ) {
			case 'save':
				$ret=true;
				foreach( $all_data as $id => $value ) {
				 	if( is_array( $value ) ) {
						if( !$this->coreMonthlySettlement->set_amount( $value['mcs_capitalsourceid'], $month, $year, $value['amount'] ) )
							$ret=false;
					}
				}

				if( $ret ) {
					$this->template->assign( 'CLOSE', 1 );
					break;
				}
			default:
				if( $month==0 && $year==0 ) {
					$timestamp=$this->coreMonthlySettlement->get_next_date();
					$month=date( 'm', $timestamp );
					$year=date( 'Y', $timestamp );
					$new = 1;
					$this->template->assign( 'NEW', 1 );
				} elseif ( $all_data['new'] == 1 ) {
					$this->template->assign( 'NEW', 1 );
				}

				if( $month > 0 && $year > 0 ) {
					$all_ids=$this->coreCapitalSources->get_valid_ids();
					$all_data=array();
					foreach( $all_ids as $id ) {
						if( $new == 1 ) {
							$amount = $this->coreMonthlySettlement->get_amount( $id, date( 'm', mktime( 0, 0, 0, $month-1, 1, $year ) ), date( 'Y', mktime( 0, 0, 0, $month-1, 1, $year ) ) );;
							$amount += round( $lastamount+$this->coreMoneyFlows->get_monthly_capitalsource_movement( $id, $month, $year ), 2 );
						} else {
							$amount = $this->coreMonthlySettlement->get_amount( $id,$month, $year );
						}
						$all_data[]=array(
							'id'      => $id,
							'comment' => $this->coreCapitalSources->get_comment( $id ),
							'amount'  => $amount
						);
					}

					$month = array(
						'nummeric' => sprintf( '%02d', $month ),
						'name'     => $this->coreText->get_month( $month )
					);

					$this->template->assign( 'MONTH',          $month             );
					$this->template->assign( 'YEAR' ,          $year              );
					$this->template->assign( 'ALL_DATA',       $all_data          );
					$this->template->assign( 'COUNT_ALL_DATA', count( $all_data ) );
					$this->template->assign( 'ERRORS',         $this->get_errors() );
				}
				break;
		}

		$this->template->assign( 'CURRENCY', $this->coreCurrencies->get_displayed_currency() );
		$this->template->assign( 'ERRORS',   $this->get_errors() );

		$this->parse_header( 1 );
		return $this->fetch_template( 'display_edit_monthlysettlement.tpl' );
	}

	function display_delete_monthlysettlement( $realaction, $month, $year ) {

		switch( $realaction ) {
			case 'yes':
				if( $this->coreMonthlySettlement->delete_amount( $month, $year ) ) {
					$this->template->assign( 'ENV_REFERER', $this->index_php.'?action=list_monthlysettlements' );
					$this->template->assign( 'CLOSE', 1 );
					break;
				}
			default:
				$all_ids=$this->coreCapitalSources->get_valid_ids();
				foreach( $all_ids as $id ) {
					$all_data[]=array(
						'id'      => $id,
						'comment' => $this->coreCapitalSources->get_comment( $id ),
						'amount'  => $this->coreMonthlySettlement->get_amount( $id, $month, $year )
					);
				}
				$sumamount=$this->coreMonthlySettlement->get_sum_amount( $month, $year );

				$month = array(
					'nummeric' => sprintf( '%02d', $month ),
					'name'     => $this->coreText->get_month( $month )
				);
				$this->template->assign( 'SUMAMOUNT',      $sumamount         );
				$this->template->assign( 'MONTH',          $month             );
				$this->template->assign( 'YEAR' ,          $year              );
				$this->template->assign( 'ALL_DATA',       $all_data          );
				$this->template->assign( 'COUNT_ALL_DATA', count( $all_data ) );
				break;
		}

		$this->template->assign( 'CURRENCY', $this->coreCurrencies->get_displayed_currency() );
		$this->template->assign( 'ERRORS',   $this->get_errors() );

		$this->parse_header( 1 );
		return $this->fetch_template( 'display_delete_monthlysettlement.tpl' );
	}
}
?>
