<?php

//
// Copyright (c) 2014-2015 Oliver Lehmann <oliver@laladev.org>
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
// $Id: moduleImportedMoneyFlows.php,v 1.7 2015/11/01 12:14:09 olivleh1 Exp $
//
namespace client\module;

use client\handler\ImportedMoneyflowControllerHandler;

class moduleImportedMoneyFlows extends module {

	public final function __construct() {
		parent::__construct();
	}

	public final function display_add_importedmoneyflow($realaction, $all_data) {
		$addMoneyflow = ImportedMoneyflowControllerHandler::getInstance()->showAddImportedMoneyflows();

		$this->parse_header( 0, 1, 'display_add_importedmoneyflows_bs.tpl' );

		if ($addMoneyflow == null) {
			$this->template_assign_raw( 'NUM_MONEYFLOWS', 0 );
		} else {
			$contractpartner = $this->sort_contractpartner( $addMoneyflow ['contractpartner'] );

			$this->template_assign_raw( 'NUM_MONEYFLOWS', count( $addMoneyflow ['importedmoneyflows'] ) );
			$this->template_assign_raw( 'JSON_FORM_DEFAULTS', json_encode( $addMoneyflow ['importedmoneyflows'] ) );
			$this->template_assign_raw( 'JSON_POSTINGACCOUNTS', json_encode( $addMoneyflow ['postingaccounts'] ) );
			$this->template_assign_raw( 'JSON_CONTRACTPARTNER', $this->json_encode_with_null_to_empty_string( $contractpartner ) );

			$this->template_assign( 'CAPITALSOURCE_VALUES', $addMoneyflow ['capitalsources'] );
			$this->template_assign( 'CONTRACTPARTNER_VALUES', $contractpartner );
			$this->template_assign( 'POSTINGACCOUNT_VALUES', $addMoneyflow ['postingaccounts'] );
		}

		return $this->fetch_template( 'display_add_importedmoneyflows_bs.tpl' );
	}

	public final function process_importedmoneyflow($all_data, $all_subdata) {
		$insert_moneyflowsplitentries = array ();

		if (is_array( $all_subdata )) {
			foreach ( $all_subdata as $splitEntry ) {
				if ($splitEntry ['amount'] != null && $splitEntry ['comment'] != null && $splitEntry ['mpa_postingaccountid'] != null) {
					if ($splitEntry ['moneyflowsplitentryid'] == - 1) {
						$insert_moneyflowsplitentries [] = $splitEntry;
					}
				}
			}
		}
		if ($all_data ['delete'] == 1) {
			$ret = ImportedMoneyflowControllerHandler::getInstance()->deleteImportedMoneyflowById( $all_data ['importedmoneyflowid'] );
		} else {
			// TODO: Split Entries + RETURN VALUE
			$ret = ImportedMoneyflowControllerHandler::getInstance()->importImportedMoneyflows( array (
					$all_data
			) );
		}

		return $this->handleReturnForAjax( $ret );
	}
}
?>
