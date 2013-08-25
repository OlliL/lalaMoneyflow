<?php
use rest\client\CallServer;
use rest\client\mapper\ClientArrayMapperEnum;
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
// $Id: moduleContractPartners.php,v 1.20 2013/08/25 01:03:32 olivleh1 Exp $
//

require_once 'module/module.php';

class moduleContractPartners extends module {

	public final function __construct() {
		parent::__construct();
		parent::addMapper( 'rest\client\mapper\ArrayToContractpartnerMapper', ClientArrayMapperEnum::CONTRACTPARTNER_ARRAY_TYPE );
	}

	public final function display_list_contractpartners($letter) {
		$all_index_letters = CallServer::getInstance()->getAllContractpartnerInitials();

		if (! $letter) {
			$num_sources = CallServer::getInstance()->getAllContractpartnerCount();
			if ($num_sources < $this->coreTemplates->get_max_rows()) {
				$letter = 'all';
			}
		}

		if ($letter == 'all') {
			$contractpartnerArray = CallServer::getInstance()->getAllContractpartner();
		} elseif (! empty( $letter )) {
			$contractpartnerArray = CallServer::getInstance()->getAllContractpartnerByInitial( $letter );
		} else {
			$contractpartnerArray = array ();
		}
		if (is_array( $contractpartnerArray )) {
			$all_data = parent::mapArray( $contractpartnerArray );
			$this->template->assign( 'ALL_DATA', $all_data );
		}
		$this->template->assign( 'COUNT_ALL_DATA', count( $all_data ) );
		$this->template->assign( 'ALL_INDEX_LETTERS', $all_index_letters );

		$this->parse_header();
		return $this->fetch_template( 'display_list_contractpartners.tpl' );
	}

	public final function display_edit_contractpartner($realaction, $contractpartnerid, $all_data) {
		switch ($realaction) {
			case 'save' :
				$all_data ['contractpartnerid'] = $contractpartnerid;
				$contractpartner = parent::map( $all_data, ClientArrayMapperEnum::CONTRACTPARTNER_ARRAY_TYPE );
				if ($contractpartnerid == 0)
					$ret = CallServer::getInstance()->createContractpartner( $contractpartner );
				else
					$ret = CallServer::getInstance()->updateContractpartner( $contractpartner );

				if ($ret) {
					$this->template->assign( 'CLOSE', 1 );
				} else {
					$this->template->assign( 'ALL_DATA', $all_data );
				}
				break;
			default :
				if ($contractpartnerid > 0) {
					$contractpartner = CallServer::getInstance()->getContractpartnerById( $contractpartnerid );
					if ($contractpartner) {
						$all_data = parent::map( $contractpartner );
						$this->template->assign( 'ALL_DATA', $all_data );
					}
				}
				break;
		}

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
					$contractpartner = CallServer::getInstance()->getContractpartnerById( $contractpartnerid );
					if ($contractpartner) {
						$all_data = parent::map( $contractpartner );
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
