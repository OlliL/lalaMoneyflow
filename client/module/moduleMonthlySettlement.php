<?php

require_once 'module/module.php';
require_once 'core/coreMonthlySettlement.php';
require_once 'core/coreCapitalSources.php';

class moduleMonthlySettlement extends module {

	function moduleMonthlySettlement() {
		$this->module();
		$this->coreMonthlySettlement=new coreMonthlySettlement();
		$this->coreCapitalSources=new coreCapitalSources();
	}

	function display_show_monthlysettlement( $month = 0, $year = 0 ) {
		if( $month==0 ) $month=date("m",mktime(0, 0, 0, date("n")-1, 1));
		if( $year==0  )  $year=date("Y",mktime(0, 0, 0, date("n")-1, 1));
		$all_ids=$this->coreCapitalSources->get_valid_ids($month,$year,$month,$year);
		
		foreach($all_ids as $id) {
			$all_data[]=array(
				'id'      => $id,
				'comment' => $this->coreCapitalSources->get_comment($id),
				'amount'  => $this->coreMonthlySettlement->get_amount($id,$month,$year)
			);
		}
				
		$this->template->assign("YEAR",    $year    );
		$this->template->assign("MONTH",   $month   );
		$this->template->assign("ALL_DATA",$all_data);
		
		$this->parse_header();
		return $this->template->fetch("./display_edit_monthlysettlement.tpl");
	}

	function edit_monthlysettlement() {
		$month=$_POST['month'];
		$year=$_POST['year'];

		if(!empty($month) && !empty($year)) {
			switch($_POST['realaction']) {
				case 'save':
					foreach($_POST['id'] as $id => $value )
						$this->coreMonthlySettlement->set_amount($id,$month,$year,$_POST['amount'][$id]);
					break;
				case 'delete':
					$this->coreMonthlySettlement->delete_amount($month,$year);
					break;
				case 'load':
					break;
			}
		}
		
		return $this->display_show_monthlysettlement($month,$year);
	}
}
?>
