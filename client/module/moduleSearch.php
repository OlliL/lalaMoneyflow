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
# $Id: moduleSearch.php,v 1.8 2007/07/14 16:36:56 olivleh1 Exp $
#

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
		$this->template->assign( 'ERRORS',                 $this->get_errors() );

		$this->parse_header();
		return $this->fetch_template( 'display_search.tpl' );
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

		if( empty($searchparams['contractpartnerid']) && empty($searchparams['pattern']) ) {
			add_error( 23);
		} else {
			$results = $this->coreMoneyFlows->search_moneyflows( $searchparams );
		}
	    
		$this->template->assign( 'SEARCHPARAMS', $searchparams );
		$this->template->assign( 'RESULTS',      $results );
		$this->template->assign( 'CURRENCY',     $this->coreCurrencies->get_displayed_currency() );
		return $this->display_search();
	}
}
?>
