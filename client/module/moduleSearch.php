<?php
use rest\client\handler\ContractpartnerControllerHandler;
use rest\base\ErrorCode;
use rest\client\handler\MoneyflowControllerHandler;
//
// Copyright (c) 2006-2014 Oliver Lehmann <oliver@laladev.org>
// All rights reserved.
//
// Redistribution and use in source and binary forms, with or without
// modification, are permitted provided that the following conditions
// are met:
// 1. Redistributions of source code must retain the above copyright
// notice, this list of conditions and the following disclaimer
// 2. Redistributions in binary form must reproduce the above copyright
// notice, this list of conditions and the following disclaimer in the
// documentation and/or other materials provided with the distribution.
//
// THIS SOFTWARE IS PROVIDED BY THE AUTHOR AND CONTRIBUTORS ``AS IS'' AND
// ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
// IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
// ARE DISCLAIMED. IN NO EVENT SHALL THE AUTHOR OR CONTRIBUTORS BE LIABLE
// FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
// DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS
// OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION)
// HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
// LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY
// OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF
// SUCH DAMAGE.
//
// $Id: moduleSearch.php,v 1.31 2014/02/23 16:53:20 olivleh1 Exp $
//

require_once 'module/module.php';

class moduleSearch extends module {

	public final function moduleSearch() {
		parent::__construct();
	}

	public final function display_search($searchparams = null) {
		$contractpartner_values = MoneyflowControllerHandler::getInstance()->showSearchMoneyflow();
		if (empty( $searchparams )) {
			$searchparams ['grouping1'] = 'year';
			$searchparams ['grouping2'] = 'month';
			$searchparams ['order'] = 'grouping';
		}
		$this->template->assign( 'SEARCHPARAMS', $searchparams );
		$this->template->assign( 'CONTRACTPARTNER_VALUES', $contractpartner_values );
		$this->template->assign( 'ERRORS', $this->get_errors() );

		$this->parse_header();
		return $this->fetch_template( 'display_search.tpl' );
	}

	public final function do_search($searchstring, $contractpartner, $startdate, $enddate, $equal, $casesensitive, $regexp, $minus, $grouping1, $grouping2, $order) {
		if ($equal)
			$searchparams ['equal'] = 1;
		if ($casesensitive)
			$searchparams ['casesensitive'] = 1;
		if ($regexp)
			$searchparams ['regexp'] = 1;
		if ($minus)
			$searchparams ['minus'] = 1;

		$searchparams ['mcp_contractpartnerid'] = $contractpartner;
		$searchparams ['pattern'] = stripslashes( $searchstring );
		$searchparams ['startdate'] = $startdate;
		$searchparams ['enddate'] = $enddate;
		$searchparams ['grouping1'] = $grouping1;
		$searchparams ['grouping2'] = $grouping2;
		$searchparams ['order'] = $order;

		$data_is_valid = true;

		if (! empty( $startdate )) {
			if (! dateIsValid( $startdate )) {
				add_error( ErrorCode::DATE_FORMAT_NOT_CORRECT, array (
						GUI_DATE_FORMAT
				) );
				$searchparams ['startdate_error'] = 1;
				$data_is_valid = false;
			}
		}

		if (! empty( $enddate )) {
			if (! dateIsValid( $enddate )) {
				add_error( ErrorCode::DATE_FORMAT_NOT_CORRECT, array (
						GUI_DATE_FORMAT
				) );
				$searchparams ['enddate_error'] = 1;
				$data_is_valid = false;
			}
		}

		if ($data_is_valid) {

			$searchMoneyflows = MoneyflowControllerHandler::getInstance()->searchMoneyflows( $searchparams );
			$contractpartner_values = $searchMoneyflows ['contractpartner'];

			if ($searchMoneyflows ['result'] == false) {
				$data_is_valid = false;
				foreach ( $searchMoneyflows ['errors'] as $validationResult ) {
					$error = $validationResult ['error'];
					add_error( $error );
				}
			}

			if ($data_is_valid) {
				$results = $searchMoneyflows ['search_results'];
				if (is_array( $results ) && count( $results ) > 0) {
					if ($order) {
						if ($order === 'grouping') {
							if ($grouping1 == 'contractpartner') {
								$sortOrder1 = SORT_ASC | SORT_STRING;
							} else {
								$sortOrder1 = SORT_ASC;
							}
							if ($grouping2 == 'contractpartner') {
								$sortOrder2 = SORT_ASC | SORT_STRING;
							} else {
								$sortOrder2 = SORT_ASC;
							}
							foreach ( $results as $key => $result ) {
								if ($grouping1 == 'contractpartner') {
									$sortKey1 [$key] = strtolower( $result ['name'] );
								} else {
									$sortKey1 [$key] = $result [$grouping1];
								}
								if ($grouping2 == 'contractpartner') {
									$sortKey2 [$key] = strtolower( $result ['name'] );
								} else {
									$sortKey2 [$key] = $result [$grouping2];
								}
							}
							array_multisort( $sortKey1, $sortOrder1, $sortKey2, $sortOrder2, $results );
						} else {
							if ($order == 'comment') {
								$sortOrder = SORT_ASC | SORT_STRING;
							} else {
								$sortOrder = SORT_ASC;
							}
							foreach ( $results as $key => $result ) {
								if ($order == 'comment') {
									$sortKey [$key] = strtolower( $result ['comment'] );
								} else {
									$sortKey [$key] = $result [$order];
								}
							}
							var_dump( $order );
							array_multisort( $sortKey, $sortOrder, $results );
						}
					}

					foreach ( array_keys( $results [0] ) as $column ) {
						$columns [$column] = 1;
					}

					$this->template->assign( 'SEARCH_DONE', 1 );
					$this->template->assign( 'COLUMNS', $columns );
					$this->template->assign( 'RESULTS', $results );
				} else {
					add_error( ErrorCode::NO_DATA_FOUND );
				}
			}

			$this->template->assign( 'SEARCHPARAMS', $searchparams );
			$this->template->assign( 'CONTRACTPARTNER_VALUES', $contractpartner_values );
			$this->template->assign( 'ERRORS', $this->get_errors() );

			$this->parse_header();
			return $this->fetch_template( 'display_search.tpl' );
		}

		return $this->display_search( $searchparams );
	}
}

?>
