<?php

/*
	$Id: moduleMonthlySettlement.php,v 1.3 2005/03/05 16:48:47 olivleh1 Exp $
*/

require_once 'module/module.php';
require_once 'core/coreMonthlySettlement.php';
require_once 'core/coreCapitalSources.php';

class moduleMonthlySettlement extends module {

	function moduleMonthlySettlement() {
		$this->module();
		$this->coreMonthlySettlement=new coreMonthlySettlement();
		$this->coreCapitalSources=new coreCapitalSources();
	}

	function display_list_monthlysettlements( $month, $year ) {

		if( !$year )
			$year=date('Y');
	
		$years = $this->coreMonthlySettlement->get_all_years();
		$temp_months = $this->coreMonthlySettlement->get_all_months($year);
		if( is_array( $temp_months ) ) {
			foreach( $temp_months as $key => $value ) {
				$months[] = array(
					'nummeric' => sprintf( '%02d', $value ),
					'name'     => strftime( '%B', strtotime( "$value/1/$year" ) )
				);
			}
		}

		if( $month > 0 && $year > 0 ) {
			$all_ids=$this->coreCapitalSources->get_valid_ids( $month, $year, $month, $year );
			foreach($all_ids as $id) {
				$all_data[]=array(
					'id'      => $id,
					'comment' => $this->coreCapitalSources->get_comment($id),
					'amount'  => $this->coreMonthlySettlement->get_amount($id,$month,$year)
				);
			}
			$month = array(
				'nummeric' => sprintf( '%02d', $month ),
				'name'     => strftime( '%B', strtotime( "$month/1/$year" ) )
			);
			$this->template->assign( 'MONTH',          $month             );
			$this->template->assign( 'YEAR' ,          $year              );
			$this->template->assign( 'ALL_DATA',       $all_data          );
			$this->template->assign( 'COUNT_ALL_DATA', count( $all_data ) );
		}

		$this->template->assign( 'ALL_YEARS',     $years  );
		$this->template->assign( 'ALL_MONTHS',    $months );
		$this->template->assign( 'SELECTED_YEAR', $year   );
		
		$this->parse_header();
		return $this->template->fetch('./display_list_monthlysettlements.tpl');
	}

	function display_edit_monthlysettlement( $realaction, $month, $year, $all_data ) {

		switch( $realaction ) {
			case 'save':
				$ret=true;
				foreach( $all_data as $id => $value ) {
					if( !$this->coreMonthlySettlement->set_amount( $value['id'], $month, $year, $value['amount'] ) )
						$ret=false;
				}

				if( $ret )
					$this->template->assign( 'CLOSE', 1 );
				break;
			default:
				if( $month==0 && $year==0 ) {
					$timestamp=$this->coreMonthlySettlement->get_next_date();
					$month=date( 'm', $timestamp );
					$year=date( 'Y', $timestamp );
					$this->template->assign( 'NEW', 1 );
				} elseif ( $all_data['new'] == 1 ) {
					$this->template->assign( 'NEW', 1 );
				}

				if( $month > 0 && $year > 0 ) {
					$all_ids=$this->coreCapitalSources->get_valid_ids( $month, $year, $month, $year );
					$all_data=array();
					foreach($all_ids as $id) {
						$all_data[]=array(
							'id'      => $id,
							'comment' => $this->coreCapitalSources->get_comment($id),
							'amount'  => $this->coreMonthlySettlement->get_amount($id,$month,$year)
						);
					}
					$month = array(
						'nummeric' => sprintf( '%02d', $month ),
						'name'     => strftime( '%B', strtotime( "$month/1/$year" ) )
					);
					$this->template->assign( 'MONTH',          $month             );
					$this->template->assign( 'YEAR' ,          $year              );
					$this->template->assign( 'ALL_DATA',       $all_data          );
					$this->template->assign( 'COUNT_ALL_DATA', count( $all_data ) );
				}
				break;
		}

		$this->template->assign( 'ERRORS', $this->get_errors() );
		
		$this->parse_header(1);
		return $this->template->fetch( './display_edit_monthlysettlement.tpl' );
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
				$all_ids=$this->coreCapitalSources->get_valid_ids( $month, $year, $month, $year );
				foreach($all_ids as $id) {
					$all_data[]=array(
						'id'      => $id,
						'comment' => $this->coreCapitalSources->get_comment($id),
						'amount'  => $this->coreMonthlySettlement->get_amount($id,$month,$year)
					);
				}
				$month = array(
					'nummeric' => sprintf( '%02d', $month ),
					'name'     => strftime( '%B', strtotime( "$month/1/$year" ) )
				);
				$this->template->assign( 'MONTH',          $month             );
				$this->template->assign( 'YEAR' ,          $year              );
				$this->template->assign( 'ALL_DATA',       $all_data          );
				$this->template->assign( 'COUNT_ALL_DATA', count( $all_data ) );
				break;
		}

		$this->template->assign( 'ERRORS', $this->get_errors() );
		
		$this->parse_header(1);
		return $this->template->fetch( './display_delete_monthlysettlement.tpl' );
	}
}
?>
