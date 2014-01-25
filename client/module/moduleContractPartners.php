<?php
use rest\client\CallServer;
use rest\base\ErrorCode;
//
// Copyright (c) 2005-2013 Oliver Lehmann <oliver@FreeBSD.org>
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
// $Id: moduleContractPartners.php,v 1.26 2014/01/25 01:47:03 olivleh1 Exp $
//

require_once 'module/module.php';

class moduleContractPartners extends module {

	public final function __construct() {
		parent::__construct();
	}

	public final function display_list_contractpartners($letter) {
		$maxRows = $this->coreTemplates->get_max_rows();
		$listContractpartner = CallServer::getInstance()->listContractpartner( $maxRows, $letter );

		$all_index_letters = $listContractpartner ['initials'];
		$all_data = $listContractpartner ['contractpartner'];

		$this->template->assign( 'ALL_DATA', $all_data );
		$this->template->assign( 'COUNT_ALL_DATA', count( $all_data ) );
		$this->template->assign( 'ALL_INDEX_LETTERS', $all_index_letters );

		$this->parse_header();
		return $this->fetch_template( 'display_list_contractpartners.tpl' );
	}

	public final function display_edit_contractpartner($realaction, $contractpartnerid, $all_data) {
		switch ($realaction) {
			case 'save' :
				$all_data ['contractpartnerid'] = $contractpartnerid;

				if ($contractpartnerid == 0)
					$ret = CallServer::getInstance()->createContractpartner( $all_data );
				else
					$ret = CallServer::getInstance()->updateContractpartner( $all_data );

				if ($ret === true) {
					$this->template->assign( 'CLOSE', 1 );
				} else {
					foreach ( $ret ['errors'] as $validationResult ) {
						$error = $validationResult ['error'];

						add_error( $error );

						switch ($error) {
							case ErrorCode::NAME_ALREADY_EXISTS :
								$all_data ['name_error'] = 1;
								break;
						}
					}
					$this->template->assign( 'ALL_DATA', $all_data );
				}
			default :
				if (! is_array( $all_data )) {
					if ($contractpartnerid > 0) {
						$all_data = CallServer::getInstance()->getContractpartnerById( $contractpartnerid );
						if ($all_data) {
						}
					}
				}
				break;
		}

		$this->template->assign( 'ALL_DATA', $all_data );
		$this->template->assign( 'ERRORS', $this->get_errors() );

		$this->parse_header( 1 );
		return $this->fetch_template( 'display_edit_contractpartner.tpl' );
	}

	public final function display_delete_contractpartner($realaction, $contractpartnerid) {
		switch ($realaction) {
			case 'yes' :
				if (CallServer::getInstance()->deleteContractpartner( $contractpartnerid )) {
					$this->template->assign( 'CLOSE', 1 );
					break;
				}
			default :
				if ($contractpartnerid > 0) {
					$all_data = CallServer::getInstance()->getContractpartnerById( $contractpartnerid );
					if ($all_data) {
						$this->template->assign( 'ALL_DATA', $all_data );
					}
				}
				break;
		}

		$this->template->assign( 'ERRORS', $this->get_errors() );

		$this->parse_header( 1 );
		return $this->fetch_template( 'display_delete_contractpartner.tpl' );
	}
}
?>
