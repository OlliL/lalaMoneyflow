<?php

/*
	$Id: module.php,v 1.2 2005/03/05 12:19:49 olivleh1 Exp $
*/

require_once 'Smarty.class.php';

class module {
	function module() {
		$this->template = new Smarty;
		$this->template->register_modifier( 'number_format', 'my_number_format' );
		$this->template->assign( 'ENV_INDEX_PHP', 'index.php' );
		if ($_POST['sr'] == 1 || $_GET['sr'] == 1) {
			$this->template->assign( 'ENV_REFERER', $_SERVER['HTTP_REFERER'] );
		} else {
			$this->template->assign( 'ENV_REFERER', $_POST['REFERER']?$_POST['REFERER']:$_GET['REFERER'] );
		}
	}
	
	function parse_header( $nonavi=0 ) {
		$this->template->assign( 'REPORTS_YEAR',  date('Y') );
		$this->template->assign( 'REPORTS_MONTH', date('m') );
		$this->template->assign( 'NO_NAVIGATION', $nonavi   );

		$header=$this->template->fetch('./display_header.tpl');
		$this->template->assign( 'HEADER', $header );

		$footer=$this->template->fetch('./display_footer.tpl');
		$this->template->assign( 'FOOTER', $footer );
	}	

	function get_errors() {
		global $ERRORS;
		return $ERRORS;
	}
}
?>
