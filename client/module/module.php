<?php

require_once 'Smarty.class.php';
require_once 'core/coreMoneyFlows.php';

class module {
	function module() {
		$this->template = new Smarty;
		$this->template->register_modifier("number_format","my_number_format");
		$this->template->assign( "ENV_INDEX_PHP", "index.php" );
		if ($_POST['sr'] == 1 || $_GET['sr'] == 1) {
			$this->template->assign( "ENV_REFERER",   $_SERVER['HTTP_REFERER'] );
		} else {
			$this->template->assign( "ENV_REFERER",   $_POST['REFERER']?$_POST['REFERER']:$_GET['REFERER'] );
		}
	}
	
	function parse_header($nonavi=0) {
		$this->coreMoneyFlows=new coreMoneyFlows();
		$years = $this->coreMoneyFlows->get_all_years();
		$this->template->assign("YEARS",$years);
		if( empty($_POST['header_year']) )
			$this->template->assign("HEADER_YEAR",date("Y"));
		else
			$this->template->assign("HEADER_YEAR",$_POST['header_year']);

		if( empty($_POST['header_month']) )
			$this->template->assign("HEADER_MONTH",date("m"));
		else
			$this->template->assign("HEADER_MONTH",$_POST['header_month']);
		

		$this->template->assign( "NO_NAVIGATION", $nonavi );
		$header=$this->template->fetch("./display_header.tpl");
		$this->template->assign( "HEADER", $header );

		$footer=$this->template->fetch("./display_footer.tpl");
		$this->template->assign( "FOOTER", $footer );
	}	

	function get_errors() {
		global $ERRORS;
		return $ERRORS;
	}
}
?>
