<?php
//
// Copyright (c) 2014-2015 Oliver Lehmann <lehmann@ans-netz.de>
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
// $Id: moduleContractPartnerAccounts.php,v 1.5 2015/09/13 17:43:11 olivleh1 Exp $
//
namespace client\module;

use base\ErrorCode;
use client\handler\ContractpartnerAccountControllerHandler;

class moduleContractPartnerAccounts extends module {

	public final function __construct() {
		parent::__construct();
	}

	public final function display_list_contractpartneraccounts($contractpartnerid) {
		$listContractpartnerAccounts = ContractpartnerAccountControllerHandler::getInstance()->showContractpartnerAccountList($contractpartnerid);
		$contractpartnername = $listContractpartnerAccounts['contractpartnername'];
		$all_data = $listContractpartnerAccounts['contractpartneraccount'];

		$this->template_assign( 'ALL_DATA', $all_data );
		$this->template_assign( 'COUNT_ALL_DATA', count( $all_data ) );
		$this->template_assign( 'CONTRACTPARTNERID', $contractpartnerid);
		$this->template_assign( 'CONTRACTPARTNER_NAME', $contractpartnername );

		$this->parse_header_without_embedded( 1, 'display_list_contractpartneraccounts_bs.tpl' );
		return $this->fetch_template( 'display_list_contractpartneraccounts_bs.tpl' );
	}

	public final function display_edit_contractpartneraccount($realaction, $contractpartneraccountid, $contractpartnerid, $all_data) {
		$close = 0;
		switch ($realaction) {
			case 'save' :
				$all_data ['contractpartneraccountid'] = $contractpartneraccountid;
				$all_data ['mcp_contractpartnerid'] = $contractpartnerid;

				if ($contractpartneraccountid == 0)
					$ret = ContractpartnerAccountControllerHandler::getInstance()->createContractpartnerAccount( $all_data );
				else
					$ret = ContractpartnerAccountControllerHandler::getInstance()->updateContractpartnerAccount( $all_data );

				if ($ret === true) {
					$close = 1;
				} else {
					foreach ( $ret ['errors'] as $validationResult ) {
						$error = $validationResult ['error'];
						$variableArray = $validationResult ['variable_array'];

						$this->add_error( $error, $variableArray );

						switch ($error) {
							case ErrorCode::BANK_CODE_TO_LONG :
								$all_data ['bankcode_error'] = 1;
								break;
							case ErrorCode::ACCOUNT_NUMBER_TO_LONG :
								$all_data ['accountnumber_error'] = 1;
								break;
						}
					}
				}
			default :
				if (! is_array( $all_data )) {
					if ($contractpartneraccountid > 0) {
						$all_data = ContractpartnerAccountControllerHandler::getInstance()->showEditContractpartnerAccount( $contractpartneraccountid );
					}
				}
				break;
		}

		$this->template_assign( 'CLOSE', $close );
		if ($close == 0) {
			$this->template_assign( 'CONTRACTPARTNERACCOUNTID', $contractpartneraccountid );
			$this->template_assign( 'CONTRACTPARTNERID', $contractpartnerid );
			$this->template_assign( 'ALL_DATA', $all_data );
			$this->template_assign( 'ERRORS', $this->get_errors() );
		}
		$this->parse_header( 1 );
		return $this->fetch_template( 'display_edit_contractpartneraccount.tpl' );
	}

	public final function display_delete_contractpartneraccount($realaction, $contractpartneraccountid) {
		switch ($realaction) {
			case 'yes' :
				if (ContractpartnerAccountControllerHandler::getInstance()->deleteContractpartnerAccount( $contractpartneraccountid )) {
					$this->template_assign( 'CLOSE', 1 );
					break;
				}
			default :
				if ($contractpartneraccountid > 0) {
					$all_data = ContractpartnerAccountControllerHandler::getInstance()->showDeleteContractpartnerAccount( $contractpartneraccountid );
					if ($all_data) {
						$this->template_assign( 'ALL_DATA', $all_data );
					}
				}
				break;
		}

		$this->template_assign( 'ERRORS', $this->get_errors() );

		$this->parse_header( 1 );
		return $this->fetch_template( 'display_delete_contractpartneraccount.tpl' );
	}
}
?>
