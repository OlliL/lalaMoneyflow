<?php

/*
	$Id: moduleSearch.php,v 1.2 2006/06/27 16:44:54 olivleh1 Exp $
*/

require_once 'module/module.php';
require_once 'core/coreMoneyFlows.php';
require_once 'core/coreContractPartners.php';

class moduleSearch extends module {

	function moduleSearch() {
		$this->module();
		$this->coreMoneyFlows=new coreMoneyFlows();
		$this->coreContractPartners=new coreContractPartners();
	}

	function display_search() {

		$contractpartner_values=$this->coreContractPartners->get_all_names();
		#var_dump($contractpartner_values);
		$this->template->assign( 'CONTRACTPARTNER_VALUES', $contractpartner_values );

		$this->parse_header();
		return $this->template->fetch( './display_search.tpl' );
	}

	function do_search( $searchstring, $contractpartner, $startdate, $enddate, $equal, $casesensitive, $regexp, $minus ) {
		if($equal)
			$searchparams['equal'] = 1;
		if($casesensitive)
			$searchparams['casesensitive'] = 1;
		if($regexp)
			$searchparams['regexp'] = 1;
		if($orderbyseries)
			$searchparams['orderbyseries'] = 1;
		if($minus)
			$searchparams['minus'] = 1;

		$searchparams['contractpartnerid']   = $contractpartner;
		$searchparams['pattern']   = stripslashes( $searchstring );
		$searchparams['startdate'] = $startdate;
		$searchparams['enddate']   = $enddate;

		$results = $this->coreMoneyFlows->search_moneyflows( $searchparams );
	    
		$this->template->assign( 'SEARCHPARAMS',   $searchparams      );
		$this->template->assign( 'RESULTS', $results );
		return $this->display_search();
	}
}
?>
