<?php

/*
	$Id: moduleReports.php,v 1.3 2005/03/05 12:24:50 olivleh1 Exp $
*/

require_once 'module/module.php';
require_once 'core/coreCapitalSources.php';
require_once 'core/coreContractPartners.php';
require_once 'core/coreMoneyFlows.php';
require_once 'core/coreMonthlySettlement.php';

class moduleReports extends module {

	function moduleReports() {
		$this->module();
		$this->coreCapitalSources=new coreCapitalSources();
		$this->coreContractPartners=new coreContractPartners();
		$this->coreMoneyFlows=new coreMoneyFlows();
		$this->coreMonthlySettlement=new coreMonthlySettlement();
	}

	function display_list_reports( $month, $year ) {

		if( !$year )
			$year=date('Y');
	
		$years = $this->coreMoneyFlows->get_all_years();
		$temp_months = $this->coreMoneyFlows->get_all_months($year);
		if( is_array( $temp_months ) ) {
			foreach( $temp_months as $key => $value ) {
				$months[] = array(
					'nummeric' => sprintf( '%02d', $value ),
					'name'     => strftime( '%B', strtotime( "$value/1/$year" ) )
				);
			}
		}

		if( $month > 0 && $year > 0 ) {
			$report=$this->generate_report( $month, $year );
			$this->template->assign( 'REPORT', $report );
		}

		$this->template->assign( 'ALL_YEARS',     $years  );
		$this->template->assign( 'ALL_MONTHS',    $months );
		$this->template->assign( 'SELECTED_YEAR', $year   );
		
		$this->parse_header();
		return $this->template->fetch('./display_list_reports.tpl');
	}

	function generate_report( $month, $year ) {

		$all_moneyflow_data=$this->coreMoneyFlows->get_all_monthly_data( $month, $year );
		$this->template->assign( 'ALL_MONEYFLOW_DATA',   $all_moneyflow_data );
		
		$all_capitalsources_ids=$this->coreCapitalSources->get_valid_ids( $month, $year, $month, $year );
		
		$i=0;
		foreach( $all_capitalsources_ids as $capitalsources_id ) {
			$summary_data[$i]['id']=$capitalsources_id;
			$summary_data[$i]['comment']=$this->coreCapitalSources->get_comment( $capitalsources_id );
			$summary_data[$i]['type']=$this->coreCapitalSources->get_type( $capitalsources_id );
			$summary_data[$i]['state']=$this->coreCapitalSources->get_state( $capitalsources_id );
			$summary_data[$i]['lastamount']=$this->coreMonthlySettlement->get_amount( $capitalsources_id, date( 'm', mktime(0, 0, 0, $month-1, 1) ), date( 'Y', mktime(0, 0, 0, $month-1, 1) ) );
			$summary_data[$i]['fixamount']=$this->coreMonthlySettlement->get_amount($capitalsources_id,$month,$year);
			$summary_data[$i]['calcamount']=round( $summary_data[$i]['lastamount']+$this->coreMoneyFlows->get_monthly_capitalsource_movement( $capitalsources_id, $month, $year ), 2 );
			$summary_data[$i]['difference']=$summary_data[$i]['fixamount']-$summary_data[$i]['calcamount'];

			$lastamount+=$summary_data[$i]['lastamount'];
			$fixamount+=$summary_data[$i]['fixamount'];
			$calcamount+=$summary_data[$i]['calcamount'];

			$i++;
		}

		$month = array(
			'nummeric' => sprintf( '%02d', $month ),
			'name'     => strftime( '%B', strtotime( "$month/1/$year" ) )
		);

		$capitalsource_values=$this->coreCapitalSources->get_valid_comments( $month, $year, $month, $year );
		$contractpartner_values=$this->coreContractPartners->get_all_names();
		
		$this->template->assign( 'MONTH',                  $month                  );
		$this->template->assign( 'YEAR' ,                  $year                   );
		$this->template->assign( 'SUMMARY_DATA',           $summary_data           );
		$this->template->assign( 'LASTAMOUNT',             $lastamount             );
		$this->template->assign( 'FIXAMOUNT',              $fixamount              );
		$this->template->assign( 'CALCAMOUNT',             $calcamount             );
		$this->template->assign( 'CAPITALSOURCE_VALUES',   $capitalsource_values   );
		$this->template->assign( 'CONTRACTPARTNER_VALUES', $contractpartner_values );

		$this->parse_header();
		return $this->template->fetch( './display_generate_report.tpl' );
	}
}
?>
