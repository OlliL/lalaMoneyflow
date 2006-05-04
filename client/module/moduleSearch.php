<?php

/*
	$Id: moduleSearch.php,v 1.1 2006/05/04 19:45:26 olivleh1 Exp $
*/

require_once 'module/module.php';
require_once 'core/coreMoneyFlows.php';

class moduleSearch extends module {

	function moduleSearch() {
		$this->module();
		$this->coreMoneyFlows=new coreMoneyFlows();
	}

	function display_search() {
		$this->parse_header();
		return $this->template->fetch( './display_search.tpl' );
	}

	function do_search( $searchstring, $startdate, $enddate, $equal, $casesensitive, $regexp, $minus ) {
		if($equal)
			$searchparams["equal"] = 1;
		if($casesensitive)
			$searchparams["casesensitive"] = 1;
		if($regexp)
			$searchparams["regexp"] = 1;
		if($orderbyseries)
			$searchparams["orderbyseries"] = 1;
		if($minus)
			$searchparams["minus"] = 1;

		$searchparams["pattern"]   = stripslashes( $searchstring );
		$searchparams["startdate"] = $startdate;
		$searchparams["enddate"]   = $enddate;

		$results = $this->coreMoneyFlows->search_moneyflows( $searchparams );
	    
		$this->template->assign( "SEARCHPARAMS",   $searchparams      );
		$this->template->assign( 'RESULTS', $results );
		return $this->display_search();
	}
}
?>
