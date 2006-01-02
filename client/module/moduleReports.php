<?php

/*
	$Id: moduleReports.php,v 1.14 2006/01/02 18:05:26 olivleh1 Exp $
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

	function display_list_reports( $month, $year, $sortby, $order ) {

		if( !$year )
			$year=date( 'Y' );

		$years=$this->coreMoneyFlows->get_all_years();
		$temp_months=$this->coreMoneyFlows->get_all_months( $year );
		if( is_array( $temp_months ) ) {
			foreach( $temp_months as $key => $value ) {
				$months[] = array(
					'nummeric' => sprintf( '%02d', $value ),
					'name'     => strftime( '%B', strtotime( "$value/1/$year" ) )
				);
			}
		}

		if( $month > 0 && $year > 0 ) {
			$report=$this->generate_report( $month, $year, $sortby, $order );
			$this->template->assign( 'REPORT', $report );
		}

		$this->template->assign( 'ALL_YEARS',      $years  );
		$this->template->assign( 'ALL_MONTHS',     $months );
		$this->template->assign( 'SELECTED_YEAR',  $year   );

		$this->parse_header();
		return $this->template->fetch( './display_list_reports.tpl' );
	}

	function generate_report( $month, $year, $sortby, $order ) {

		switch( $order ) {
			case 'DESC':	$neworder='ASC';
					break;
			case 'ASC':	$neworder='DESC';
					break;
			default:	$order='';
					$neworder='ASC';
		}

		$all_moneyflow_data=$this->coreMoneyFlows->get_all_monthly_joined_data( $month, $year, $sortby, $order );
		$this->template->assign( 'ALL_MONEYFLOW_DATA', $all_moneyflow_data );

		$all_capitalsources_ids=$this->coreCapitalSources->get_valid_ids( "$year-$month-1", "$year-$month-1", $sortby, $order );

		$i=0;
		$lastamount=0;
		$fixamount=0;
		$calcamount=0;
		foreach( $all_capitalsources_ids as $capitalsources_id ) {
			$summary_data[$i]['id']=$capitalsources_id;
			$summary_data[$i]['comment']=$this->coreCapitalSources->get_comment( $capitalsources_id );
			$summary_data[$i]['type']=$this->coreCapitalSources->get_type( $capitalsources_id );
			$summary_data[$i]['state']=$this->coreCapitalSources->get_state( $capitalsources_id );
			$summary_data[$i]['lastamount']=$this->coreMonthlySettlement->get_amount( $capitalsources_id, date( 'm', mktime( 0, 0, 0, $month-1, 1, $year ) ), date( 'Y', mktime( 0, 0, 0, $month-1, 1, $year ) ) );
			$summary_data[$i]['fixamount']=$this->coreMonthlySettlement->get_amount( $capitalsources_id, $month,$year );
			$summary_data[$i]['calcamount']=round( $summary_data[$i]['lastamount']+$this->coreMoneyFlows->get_monthly_capitalsource_movement( $capitalsources_id, $month, $year ), 2 );
			$summary_data[$i]['difference']=$summary_data[$i]['fixamount']-$summary_data[$i]['calcamount'];

			$lastamount+=$summary_data[$i]['lastamount'];
			$mon_calcamount+=$summary_data[$i]['calcamount'];
			$fixamount+=$summary_data[$i]['fixamount'];

			$i++;
		}

		$yea_calculatedturnover+=round( $summary_data[$i]['lastamount']+$this->coreMoneyFlows->get_monthly_capitalsource_movement( '', '', $year ), 2 );

		$monthlysettlement_exists=$this->coreMonthlySettlement->monthlysettlement_exists( $month, $year );

		$firstamount=$this->coreMonthlySettlement->get_sum_amount( 12, $year-1 );

		$month = array(
			'nummeric' => sprintf( '%02d', $month ),
			'name'     => strftime( '%B', strtotime( "$month/1/$year" ) )
		);

		$this->template->assign( 'MONTH',                    $month                    );
		$this->template->assign( 'YEAR' ,                    $year                     );
		$this->template->assign( 'SORTBY',                   $sortby                   );
		$this->template->assign( 'ORDER',                    $neworder                 );
		$this->template->assign( 'SUMMARY_DATA',             $summary_data             );
		$this->template->assign( 'FIRSTAMOUNT',              $firstamount              );
		$this->template->assign( 'LASTAMOUNT',               $lastamount               );
		$this->template->assign( 'FIXAMOUNT',                $fixamount                );
		$this->template->assign( 'MON_CALCAMOUNT',           $mon_calcamount           );
		$this->template->assign( 'YEA_CALCULATEDTURNOVER',   $yea_calculatedturnover   );
		$this->template->assign( 'MONTHLYSETTLEMENT_EXISTS', $monthlysettlement_exists );

		$this->parse_header();
		return $this->template->fetch( './display_generate_report.tpl' );
	}
}
?>
