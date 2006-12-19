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
# $Id: moduleReports.php,v 1.22 2006/12/19 14:37:18 olivleh1 Exp $
#

require_once 'module/module.php';
require_once 'core/coreCapitalSources.php';
require_once 'core/coreContractPartners.php';
require_once 'core/coreCurrencies.php';
require_once 'core/coreMoneyFlows.php';
require_once 'core/coreMonthlySettlement.php';
if( ENABLE_JPGRAPH ) {
	require_once 'jpgraph.php';
	require_once 'jpgraph_line.php';
}

class moduleReports extends module {

	function moduleReports() {
		$this->module();
		$this->coreCapitalSources=new coreCapitalSources();
		$this->coreContractPartners=new coreContractPartners();
		$this->coreCurrencies=new coreCurrencies();
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

		if( is_array( $all_moneyflow_data ) ) {
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

			$yea_calculatedturnover+=round( $summary_data[$i]['lastamount']+$this->coreMoneyFlows->get_year_capitalsource_movement( $month, $year ), 2 );

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
			$this->template->assign( 'CURRENCY',                 $this->coreCurrencies->get_displayed_currency() );

			$this->parse_header();
			return $this->template->fetch( './display_generate_report.tpl' );
		}
	}

	function display_plot_trends( $all_data ) {
		$capitalsource_values = $this->coreCapitalSources->get_all_data();
		$this->template->assign( 'CAPITALSOURCE_VALUES', $capitalsource_values );
		
		$years=$this->coreMonthlySettlement->get_all_years();
		$this->template->assign( 'ALL_YEARS',      $years  );

		if( is_array( $all_data ) && isset($all_data[capitalsourceid]) ) {
			$this->template->assign( 'PLOT_GRAPH',       1  );
		} else {
			$all_data['endyear']  = $years[count($years)-1];
			$all_data['endmonth'] = 12;
			$this->template->assign( 'PLOT_GRAPH',       0  );
		}

		$this->template->assign( 'ALL_DATA',       $all_data  );


		$this->parse_header();
		return $this->template->fetch( './display_plot_trends.tpl' );

	}
	function plot_graph( $capitalsources_id, $startmonth, $startyear, $endmonth, $endyear ) {
	
		if( $capitalsources_id == 0 ) {
			$all_capitalsources_ids  = $this->coreCapitalSources->get_all_ids();
			$graph_comment = 'amount trend of all capitalsources';
		} else {
			$all_capitalsources_ids[] = $capitalsources_id;
			$graph_comment = 'amount trend of capitalsource "'.$this->coreCapitalSources->get_comment( $capitalsources_id ).'"';
		}
			
		# find first recorded monthly settlement
		$testmonth = $startmonth;
		$testyear  = $startyear;
		$exists    = false;
		while( !$exists  && ($testmonth < $endmonth || $testyear != $endyear) ) {
			$exists = $this->coreMonthlySettlement->monthlysettlement_exists( $testmonth, $testyear, $capitalsources_id );
			if( !$exists ) {
				if($testmonth == 12) {
					$testmonth = 1;
					$testyear++;
				} else {
					$testmonth++;
				}
			}
		}
		$startmonth = $testmonth;
		$startyear  = $testyear;

		for( $testmonth = $startmonth; $testmonth <= 12 && ! $this->coreMonthlySettlement->monthlysettlement_exists( $testmonth, $startyear, $capitalsources_id ); $testmonth++ );
		$startmonth = $testmonth;

		# find latest recorded monthly settlement
		$testmonth = $endmonth;
		$testyear  = $endyear;
		$exists    = 0;
		while( $exists == 0  && ($testmonth > $startmonth || $testyear != $startyear) ) {
			$exists = $this->coreMonthlySettlement->monthlysettlement_exists( $testmonth, $testyear, $capitalsources_id );
			if( $exists == 0 ) {
				if($testmonth == 1) {
					$testmonth = 12;
					$testyear--;
				} else {
					$testmonth--;
				}
			}
		}
		$endmonth = $testmonth;
		$endyear  = $testyear;

		$month = $startmonth;
		$year  = $startyear;
		$i=0;
		
		while( $year <= $endyear ) {
			while( $month <= 12 && ($month <= $endmonth || $year != $endyear) ) {
				foreach( $all_capitalsources_ids as $capitalsources_id ) {
					$monthly_data[$i] += $this->coreMonthlySettlement->get_amount( $capitalsources_id, $month,$year );
				}
				$monthly_x[$i] = sprintf('%02d/%02d',$month,substr($year,3,2));
				$month++;
				$i++;
			}
			$year++;
			$month=1;
		}

		$graph = new Graph(700,400);
		$graph->SetMargin(50,20,40,35);
		$graph->SetScale("intlin");
		$graph->SetMarginColor('#E6E6FA');
		$graph->SetFrame(true,array(0,0,0),0);

		$txt = new Text($graph_comment."\nstarting from ".sprintf('%02d/%02d',$startmonth,substr($startyear,3,2)).' until '.sprintf('%02d/%02d',$endmonth,substr($endyear,3,2)));
		$txt->SetFont(FF_FONT1,FS_BOLD);
		$txt->Center(0,700);
		$txt->ParagraphAlign('center');
		$graph->AddText($txt);
	
		$p1 = new LinePlot($monthly_data);
		$p1->SetWeight(1);
		$p1->SetFillGradient('#E6E6FA','#B0C4DE');
		$graph->Add($p1);

		$graph->xaxis->title->Set("month/year");                           
		$graph->xaxis->SetTitleMargin(10);
		$graph->xaxis->SetTickLabels($monthly_x);
		$graph->xaxis->SetFont(FF_FONT0);                              
		$graph->xaxis->title->SetFont(FF_FONT1,FS_BOLD);          

		$graph->yaxis->title->Set("amount");                                         
		$graph->yaxis->SetTitleMargin(35);
		$graph->yaxis->SetFont(FF_FONT0);                              
		$graph->yaxis->title->SetFont(FF_FONT1,FS_BOLD);                              

		$graph->Stroke();
	}

}
?>
