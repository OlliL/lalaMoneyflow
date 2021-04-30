<?php

//
// Copyright (c) 2021 Oliver Lehmann <lehmann@ans-netz.de>
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
//
namespace client\module;

use client\handler\ImportedMoneyflowReceiptControllerHandler;

class moduleImportedMoneyflowReceipt extends module {

	public final function __construct() {
		parent::__construct();
	}

	public final function display_add_imported_moneyflow_receipt() {
		$this->parse_header_without_embedded( 1, 'display_add_importedmoneyflowreceipt_bs.tpl' );
		return $this->fetch_template( 'display_add_importedmoneyflowreceipt_bs.tpl' );
	}

	public final function add_imported_moneyflow_receipt($file_data) {
		$all_data = array ();
		$numFiles = count( $file_data ['name'] );
		for($i = 0; $i < $numFiles; $i ++) {
			$filecontents = file_get_contents( $file_data ['tmp_name'] [$i] );
			$all_data [] = array (
					'receipt' => $filecontents,
					'filename' => $file_data ['name'] [$i],
					'mediaType' => $file_data ['type'] [$i]
			);
		}

		$ret = ImportedMoneyflowReceiptControllerHandler::getInstance()->createImportedMoneyflowReceipts( $all_data );
		return $this->handleReturnForAjax( $ret );
	}

	public final function display_import_imported_moneyflow_receipts() {
		$all_data = ImportedMoneyflowReceiptControllerHandler::getInstance()->showImportImportedMoneyflowReceipts();
		if (is_array( $all_data ) && array_key_exists( 'importedmoneyflowreceipts', $all_data )) {
			$importedmoneyflowreceipts = $all_data ['importedmoneyflowreceipts'];
			foreach ( $importedmoneyflowreceipts as $key => $value ) {
				$importedmoneyflowreceipts [$key] ['receipt'] = base64_encode( $value ['receipt'] );
				$filename = $importedmoneyflowreceipts [$key] ['filename'];
				$amount = substr( $filename, 0, strrpos( $filename, "." ) );
				if (is_numeric( $amount )) {
					$amount = $amount / 100;
					$importedmoneyflowreceipts [$key] ['amount'] = $amount;
				}
			}

			$this->template_assign( 'NUM_IMPORTEDMONEYFLOWRECEIPTS', count( $importedmoneyflowreceipts ) );
			$this->template_assign_raw( 'JSON_FORM_DEFAULTS', json_encode( $importedmoneyflowreceipts ) );
		} else {
			$this->template_assign( 'NUM_IMPORTEDMONEYFLOWRECEIPTS', 0 );
			$this->template_assign_raw( 'JSON_FORM_DEFAULTS', "" );
		}
		$this->parse_header_without_embedded( 0, 'display_import_imported_moneyflow_receipts_bs.tpl' );
		return $this->fetch_template( 'display_import_imported_moneyflow_receipts_bs.tpl' );
	}

	public final function import_imported_moneyflow_receipt_submit($all_data) {
		if ($all_data ['delete'] == 1) {
			$ret = ImportedMoneyflowReceiptControllerHandler::getInstance()->deleteImportedMoneyflowReceiptById( $all_data ['id'] );
		} else {
			$ret = ImportedMoneyflowReceiptControllerHandler::getInstance()->importImportedMoneyflowReceipt( $all_data ['id'], $all_data ['moneyflowid'] );
		}
		return $this->handleReturnForAjax( $ret );
	}
}
?>
