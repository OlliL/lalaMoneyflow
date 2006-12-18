<?php

/*
	$Id: moduleSearch.php,v 1.3 2006/12/18 12:24:12 olivleh1 Exp $
*/

require_once 'module/module.php';
require_once 'core/coreContractPartners.php';
require_once 'core/coreCurrencies.php';
require_once 'core/coreMoneyFlows.php';

class moduleSearch extends module {

	function moduleSearch() {
		$this->module();
		$this->coreContractPartners=new coreContractPartners();
		$this->coreCurrencies=new coreCurrencies();
		$this->coreMoneyFlows=new coreMoneyFlows();
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
	    
		$this->template->assign( 'SEARCHPARAMS', $searchparams      );
		$this->template->assign( 'RESULTS',      $results );
		$this->template->assign( 'CURRENCY',     $this->coreCurrencies->get_displayed_currency() );
		return $this->display_search();
	}
}
?>
