<?php

/*
	$Id: module.php,v 1.11 2006/11/10 09:43:05 olivleh1 Exp $
*/

require_once 'Smarty.class.php';

class module {
	function module() {
		$this->template = new Smarty;
		$this->index_php='index.php';
		$this->template->register_modifier( 'number_format', 'my_number_format' );
		$this->template->assign( 'ENV_INDEX_PHP', $this->index_php );
		
		if( !empty( $_SERVER['HTTP_REFERER'] ) ) {
			$http_referer = $_SERVER['HTTP_REFERER'];
		} else {
			$http_referer = '';
		}

		if( !empty( $_POST['REFERER'] ) ) {
			$referer = $_POST['REFERER'];
		} elseif( !empty( $_GET['REFERER'] ) ) {
			$referer = $_GET['REFERER'];
		} else {
			$referer ='';
		}
		
		if ( ( !empty( $_POST['sr'] ) && $_POST['sr'] == 1 ) || ( !empty( $_GET['sr'] ) && $_GET['sr'] == 1 ) ) {
			$this->template->assign( 'ENV_REFERER', $http_referer );
		} else {
			$this->template->assign( 'ENV_REFERER', $referer );
		}
	}

	function parse_header( $nonavi=0 ) {
		$this->template->assign( 'REPORTS_YEAR',  date( 'Y' ) );
		$this->template->assign( 'REPORTS_MONTH', date( 'm' ) );
		$this->template->assign( 'ENABLE_JPGRAPH', ENABLE_JPGRAPH );
		$this->template->assign( 'NO_NAVIGATION', $nonavi   );

		$header=$this->template->fetch( './display_header.tpl' );
		$this->template->assign( 'HEADER', $header );

		$footer=$this->template->fetch( './display_footer.tpl' );
		$this->template->assign( 'FOOTER', $footer );
	}	
}
?>
