<?php

//
// Copyright (c) 2005-2015 Oliver Lehmann <oliver@laladev.org>
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
// $Id: moduleContractPartners.php,v 1.53 2016/02/03 21:30:13 olivleh1 Exp $
//
namespace client\module;

use base\ErrorCode;
use client\handler\ContractpartnerControllerHandler;
use client\util\Environment;
use base\Configuration;

class moduleContractPartners extends module {

	public final function __construct() {
		parent::__construct();
	}

	public final function display_list_contractpartners($letter, $currently_valid) {
		$listContractpartner = ContractpartnerControllerHandler::getInstance()->showContractpartnerList( $letter, $currently_valid );
		$all_index_letters = $listContractpartner ['initials'];
		$all_data = $listContractpartner ['contractpartner'];
		$currently_valid = $listContractpartner ['currently_valid'];

		$this->template_assign( 'ALL_DATA', $this->sort_contractpartner( $all_data ) );
		$this->template_assign( 'COUNT_ALL_DATA', count( $all_data ) );
		$this->template_assign( 'LETTER', $letter );
		$this->template_assign( 'ALL_INDEX_LETTERS', $all_index_letters );
		$this->template_assign( 'CURRENTLY_VALID', $currently_valid );

		$this->parse_header();
		return $this->fetch_template( 'display_list_contractpartners.tpl' );
	}

	public final function display_edit_contractpartner($contractpartnerid, $isEmbedded = false) {
		if ($contractpartnerid > 0) {
			$showEditContractpartner = ContractpartnerControllerHandler::getInstance()->showEditContractpartner( $contractpartnerid );
			$all_data = $showEditContractpartner ['contractpartner'];
			$posting_accounts = $showEditContractpartner ['postingAccounts'];
		} else {
			$posting_accounts = ContractpartnerControllerHandler::getInstance()->showCreateContractpartner();
			$all_data = array ();
		}

		$this->template_assign( 'CONTRACTPARTNERID', $contractpartnerid );
		$this->template_assign( 'POSTINGACCOUNT_VALUES', $posting_accounts );

		$this->template_assign( 'TODAY', $this->convertDateToGui( date( 'Y-m-d' ) ) );
		$this->template_assign( 'IS_EMBEDDED', $isEmbedded );
		$this->template_assign_raw( 'JSON_FORM_DEFAULTS', json_encode( $all_data ) );

		if (! $isEmbedded) {
			$this->parse_header( 1, 1, 'display_edit_contractpartner_bs.tpl' );
		} else {
			$this->template_assign("HEADER", "");
			$this->template_assign("FOOTER", "");
		}
		return $this->fetch_template( 'display_edit_contractpartner_bs.tpl' );
	}

	public final function edit_contractpartner($contractpartnerid, $all_data) {
		$all_data ['contractpartnerid'] = $contractpartnerid;

		if ($contractpartnerid == 0)
			$ret = ContractpartnerControllerHandler::getInstance()->createContractpartner( $all_data );
		else
			$ret = ContractpartnerControllerHandler::getInstance()->updateContractpartner( $all_data );

		return $this->handleReturnForAjax( $ret );
	}

	public final function display_delete_contractpartner($realaction, $contractpartnerid) {
		switch ($realaction) {
			case 'yes' :
				if (ContractpartnerControllerHandler::getInstance()->deleteContractpartner( $contractpartnerid )) {
					$this->template_assign( 'CLOSE', 1 );
					break;
				}
			default :
				if ($contractpartnerid > 0) {
					$all_data = ContractpartnerControllerHandler::getInstance()->showDeleteContractpartner( $contractpartnerid );
					if ($all_data) {
						$this->template_assign( 'ALL_DATA', $all_data );
					}
				}
				break;
		}

		$this->template_assign( 'ERRORS', $this->get_errors() );

		$this->parse_header( 1 );
		return $this->fetch_template( 'display_delete_contractpartner.tpl' );
	}
}
?>
