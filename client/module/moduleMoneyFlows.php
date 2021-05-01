<?php

//
// Copyright (c) 2005-2021 Oliver Lehmann <lehmann@ans-netz.de>
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
// $Id: moduleMoneyFlows.php,v 1.96 2017/01/01 18:26:12 olivleh1 Exp $
//
namespace client\module;

use client\handler\MoneyflowControllerHandler;
use client\handler\MoneyflowReceiptControllerHandler;

class moduleMoneyFlows extends module {

	public final function __construct() {
		parent::__construct();
	}

	public final function show_moneyflow_receipt($id) {
		if (empty( $id ))
			return;

		$receipt = MoneyflowReceiptControllerHandler::getInstance()->showMoneyflowReceipt( $id );

		switch ($receipt ['receipt_type']) {
			case 1 :
				header( 'Content-Type: image/jpeg' );
				break;
			case 2 :
				header( 'Content-Type: application/pdf' );
				break;
		}

		echo base64_decode( $receipt ['receipt'] );
	}

	public final function display_edit_moneyflow($id) {
		if ($id == 0) {
			$this->parse_header_bootstraped( 0, 'display_edit_moneyflow_bs.tpl' );
			$displayMoneyflow = MoneyflowControllerHandler::getInstance()->showAddMoneyflows();
			$this->template_assign_raw( 'JSON_FORM_DEFAULTS', '""' );
			$this->template_assign_raw( 'JSON_FORM_SPLIT_ENTRIES_DEFAULTS', '""' );
			$this->template_assign_raw( 'NEW_WINDOW', false );
		} else {
			$this->parse_header_bootstraped( 1, 'display_edit_moneyflow_bs.tpl' );
			$displayMoneyflow = MoneyflowControllerHandler::getInstance()->showEditMoneyflow( $id );
			$displayMoneyflow ['predefmoneyflows'] = array ();
			$this->template_assign_raw( 'JSON_FORM_DEFAULTS', json_encode( $displayMoneyflow ['moneyflow'] ) );
			$this->template_assign_raw( 'JSON_FORM_SPLIT_ENTRIES_DEFAULTS', json_encode( $displayMoneyflow ['moneyflow_split_entries'] ) );
			$this->template_assign_raw( 'NEW_WINDOW', true );
		}

		$contractpartner = $this->sort_contractpartner( $displayMoneyflow ['contractpartner'] );

		$this->template_assign( 'MONEYFLOWID', $id );
		$this->template_assign( 'CAPITALSOURCE_VALUES', $displayMoneyflow ['capitalsources'] );
		$this->template_assign( 'CONTRACTPARTNER_VALUES', $contractpartner );
		$this->template_assign( 'POSTINGACCOUNT_VALUES', $displayMoneyflow ['postingaccounts'] );

		$this->template_assign_raw( 'JSON_POSTINGACCOUNTS', json_encode( $displayMoneyflow ['postingaccounts'] ) );
		$this->template_assign_raw( 'JSON_PREDEFMONEYFLOWS', json_encode( $displayMoneyflow ['predefmoneyflows'] ) );
		$this->template_assign_raw( 'JSON_CONTRACTPARTNER', $this->json_encode_with_null_to_empty_string( $contractpartner ) );

		return $this->fetch_template( 'display_edit_moneyflow_bs.tpl' );
	}

	public final function edit_moneyflow($id, $all_data, $all_subdata) {
		$delete_moneyflowsplitentryids = array ();
		$update_moneyflowsplitentries = array ();
		$insert_moneyflowsplitentries = array ();

		$existingSplitEntryIds = json_decode( $all_data ['existing_split_entry_ids'] );
		$receivedSplitEntryIds = array ();

		if (is_array( $all_subdata )) {
			foreach ( $all_subdata as $splitEntry ) {
				if ($splitEntry ['amount'] != null && $splitEntry ['comment'] != null && $splitEntry ['mpa_postingaccountid'] != null) {
					if ($splitEntry ['moneyflowsplitentryid'] == - 1) {
						$insert_moneyflowsplitentries [] = $splitEntry;
					} else {
						$update_moneyflowsplitentries [] = $splitEntry;
						$receivedSplitEntryIds [] = $splitEntry ['moneyflowsplitentryid'];
					}
				}
			}

			if (is_array( $existingSplitEntryIds )) {
				$delete_moneyflowsplitentryids = array_values( array_diff( $existingSplitEntryIds, $receivedSplitEntryIds ) );
			}
		}

		$all_data ['moneyflowid'] = $id;

		if ($id > 0) {
			$ret = MoneyflowControllerHandler::getInstance()->updateMoneyflow( $all_data, $delete_moneyflowsplitentryids, $update_moneyflowsplitentries, $insert_moneyflowsplitentries );
		} else {
			$ret = MoneyflowControllerHandler::getInstance()->createMoneyflow( $all_data, $insert_moneyflowsplitentries );
		}

		return $this->handleReturnForAjax( $ret );
	}

	public final function display_delete_moneyflow($id) {
		$all_data = MoneyflowControllerHandler::getInstance()->showDeleteMoneyflow( $id );

		$this->template_assign_raw( 'JSON_FORM_DEFAULTS', json_encode( $all_data ) );
		$this->template_assign( 'MONEYFLOWID', $id );

		$this->parse_header_without_embedded( 1, 'display_delete_moneyflow_bs.tpl' );
		return $this->fetch_template( 'display_delete_moneyflow_bs.tpl' );
	}

	public final function delete_moneyflow($id) {
		$ret = MoneyflowControllerHandler::getInstance()->deleteMoneyflowById( $id );
		return $this->handleReturnForAjax( $ret );
	}

	public final function search_moneyflow_by_amount($amount, $dateFrom, $dateTil) {
		if ($amount != '') {
			$from = new \DateTime($dateFrom);
			$fromDate = $from->format('Ymd');
			$to = new \DateTime($dateTil);
			$toDate = $to->format('Ymd');
			$searchMoneyflowsByAmount = MoneyflowControllerHandler::getInstance()->searchMoneyflowsByAmount( $amount, $fromDate, $toDate );
			return $this->handleReturnForAjax( $searchMoneyflowsByAmount );
		}
	}
}
?>
