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
// $Id: modulePreDefMoneyFlows.php,v 1.60 2015/09/11 07:57:15 olivleh1 Exp $
//
namespace client\module;

use client\handler\PreDefMoneyflowControllerHandler;

class modulePreDefMoneyFlows extends module {

	public final function __construct() {
		parent::__construct();
	}

	public final function display_list_predefmoneyflows($letter) {
		$listPreDefMoneyflows = PreDefMoneyflowControllerHandler::getInstance()->showPreDefMoneyflowList( $letter );

		$all_index_letters = $listPreDefMoneyflows ['initials'];
		$all_data = $listPreDefMoneyflows ['predefmoneyflows'];

		$this->template_assign( 'ALL_DATA', $all_data );
		$this->template_assign( 'COUNT_ALL_DATA', count( $all_data ) );
		$this->template_assign( 'LETTER', $letter );
		$this->template_assign( 'ALL_INDEX_LETTERS', $all_index_letters );

		$this->parse_header_without_embedded( 0, 'display_list_predefmoneyflows_bs.tpl' );
		return $this->fetch_template( 'display_list_predefmoneyflows_bs.tpl' );
	}

	public final function display_edit_predefmoneyflow($predefmoneyflowid) {
		if ($predefmoneyflowid > 0) {
			$showEditPreDefMoneyflow = PreDefMoneyflowControllerHandler::getInstance()->showEditPreDefMoneyflow( $predefmoneyflowid );
			$all_data = $showEditPreDefMoneyflow ['predefmoneyflow'];
			$capitalsource_values = $showEditPreDefMoneyflow ['capitalsources'];
			$contractpartner_values = $showEditPreDefMoneyflow ['contractpartner'];
			$postingaccount_values = $showEditPreDefMoneyflow ['postingaccounts'];
		} else {
			$showCreatePreDefMoneyflow = PreDefMoneyflowControllerHandler::getInstance()->showCreatePreDefMoneyflow();
			$capitalsource_values = $showCreatePreDefMoneyflow ['capitalsources'];
			$contractpartner_values = $showCreatePreDefMoneyflow ['contractpartner'];
			$postingaccount_values = $showCreatePreDefMoneyflow ['postingaccounts'];
			$all_data = array ();
		}

		$this->template_assign_raw( 'PRE_JSON_FORM_DEFAULTS', json_encode( $all_data ) );
		$this->template_assign( 'PREDEFMONEYFLOWID', $predefmoneyflowid );
		$this->template_assign( 'CAPITALSOURCE_VALUES', $capitalsource_values );
		$this->template_assign( 'CONTRACTPARTNER_VALUES', $this->sort_contractpartner( $contractpartner_values ) );
		$this->template_assign( 'POSTINGACCOUNT_VALUES', $postingaccount_values );

		$this->parse_header_bootstraped( 1, 'display_edit_predefmoneyflow_bs.tpl' );
		return $this->fetch_template( 'display_edit_predefmoneyflow_bs.tpl' );
	}

	public final function edit_predefmoneyflow($predefmoneyflowid, $all_data) {
		$all_data ['predefmoneyflowid'] = $predefmoneyflowid;

		if ($predefmoneyflowid == 0)
			$ret = PreDefMoneyflowControllerHandler::getInstance()->createPreDefMoneyflow( $all_data );
		else
			$ret = PreDefMoneyflowControllerHandler::getInstance()->updatePreDefMoneyflow( $all_data );

		return $this->handleReturnForAjax( $ret );
	}

	public final function display_delete_predefmoneyflow($predefmoneyflowid) {
		$all_data = PreDefMoneyflowControllerHandler::getInstance()->showDeletePreDefMoneyflow( $predefmoneyflowid );
		$this->template_assign_raw( 'JSON_FORM_DEFAULTS', json_encode( $all_data ) );

		$this->parse_header_without_embedded( 1, 'display_delete_predefmoneyflow_bs.tpl' );
		return $this->fetch_template( 'display_delete_predefmoneyflow_bs.tpl' );
	}

	public final function delete_predefmoneyflow($predefmoneyflowid) {
		$ret = PreDefMoneyflowControllerHandler::getInstance()->deletePreDefMoneyflow( $predefmoneyflowid );
		return $this->handleReturnForAjax( $ret );
	}
}
?>
