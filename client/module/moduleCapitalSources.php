<?php
use rest\client\CallServer;
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
// $Id: moduleCapitalSources.php,v 1.25 2013/08/14 16:15:25 olivleh1 Exp $
//

require_once 'module/module.php';

class moduleCapitalSources extends module {
	const ARRAY_TYPE = 'CapitalsourceArray';

	public final function __construct() {
		parent::__construct();
		parent::addMapper( 'rest\client\mapper\ArrayToCapitalsourceMapper', self::ARRAY_TYPE );
	}

	public final function display_list_capitalsources($letter) {
		$all_index_letters = CallServer::getAllCapitalsourceInitials();

		if (! $letter) {
			$num_sources = CallServer::getAllCapitalsourceCount();
			if ($num_sources < $this->coreTemplates->get_max_rows()) {
				$letter = 'all';
			}
		}

		if ($letter == 'all') {
			$capitalsourceArray = CallServer::getAllCapitalsources();
		} elseif (! empty( $letter )) {
			$capitalsourceArray = CallServer::getAllCapitalsourcesByInitial( $letter );
		} else {
			$capitalsourceArray = array ();
		}

		if (is_array( $capitalsourceArray )) {
			$all_data = parent::mapArray( $capitalsourceArray );

			foreach ( $all_data as $key => $data ) {
				$all_data [$key] ['statecomment'] = $this->coreDomains->get_domain_meaning( 'CAPITALSOURCE_STATE', $data ['state'] );
				$all_data [$key] ['typecomment'] = $this->coreDomains->get_domain_meaning( 'CAPITALSOURCE_TYPE', $data ['type'] );
				if ($data ['mur_userid'] == USERID) {
					$all_data [$key] ['owner'] = true;
				} else {
					$all_data [$key] ['owner'] = false;
				}
			}
			$this->template->assign( 'ALL_DATA', $all_data );
		}

		$this->template->assign( 'COUNT_ALL_DATA', count( $all_data ) );
		$this->template->assign( 'ALL_INDEX_LETTERS', $all_index_letters );

		$this->parse_header();
		return $this->fetch_template( 'display_list_capitalsources.tpl' );
	}

	public final function display_edit_capitalsource($realaction, $capitalsourceid, $all_data) {
		$validfrom_orig = $all_data ['validfrom'];
		$validtil_orig = $all_data ['validtil'];

		switch ($realaction) {
			case 'save' :
				$valid_data = true;
				$all_data ['capitalsourceid'] = $capitalsourceid;
				$capitalsource = parent::map( $all_data, self::ARRAY_TYPE );
				if ($capitalsource->getValidFrom() === false) {
					add_error( 147, array (
							GUI_DATE_FORMAT
					) );
					$all_data ['validfrom'] = $validfrom_orig;
					$all_data ['validfrom_error'] = 1;
					$valid_data = false;
				}
				if ($capitalsource->getValidTil() === false) {
					add_error( 147, array (
							GUI_DATE_FORMAT
					) );
					$all_data ['validtil'] = $validtil_orig;
					$all_data ['validtil_error'] = 1;
					$valid_data = false;
				}

				if ($valid_data === true) {
					if ($capitalsourceid == 0)
						$ret = CallServer::createCapitalsource( $capitalsource );
					else
						$ret = CallServer::updateCapitalsource( $capitalsource );
				}

				if ($ret === true) {
					$this->template->assign( 'CLOSE', 1 );
					break;
				}
			default :
				if (! is_array( $all_data )) {
					if ($capitalsourceid > 0) {
						$capitalsource = CallServer::getCapitalsourceById( $capitalsourceid );
						if ($capitalsource) {
							$all_data = parent::map( $capitalsource );
						} else {
							unset( $capitalsourceid );
						}
					}

					if (! isset( $capitalsourceid )) {
						$all_data ['validfrom'] = date( 'Y-m-d' );
						$all_data ['validtil'] = MAX_YEAR;
					}
				}
				$type_values = $this->coreDomains->get_domain_data( 'CAPITALSOURCE_TYPE' );
				$state_values = $this->coreDomains->get_domain_data( 'CAPITALSOURCE_STATE' );

				$this->template->assign( 'TYPE_VALUES', $type_values );
				$this->template->assign( 'STATE_VALUES', $state_values );
				break;
		}

		if (empty( $all_data ['validfrom_error'] ))
			$all_data ['validfrom'] = convert_date_to_gui( $all_data ['validfrom'], GUI_DATE_FORMAT );
		if (empty( $all_data ['validtil_error'] ))
			$all_data ['validtil'] = convert_date_to_gui( $all_data ['validtil'], GUI_DATE_FORMAT );

		$this->template->assign( 'ALL_DATA', $all_data );
		$this->template->assign( 'CAPITALSOURCEID', $capitalsourceid );
		$this->template->assign( 'ERRORS', $this->get_errors() );

		$this->parse_header( 1 );
		return $this->fetch_template( 'display_edit_capitalsource.tpl' );
	}

	public final function display_delete_capitalsource($realaction, $capitalsourceid) {
		switch ($realaction) {
			case 'yes' :
				if (CallServer::deleteCapitalsource( $capitalsourceid )) {
					$this->template->assign( 'CLOSE', 1 );
					break;
				}
			default :
				if ($capitalsourceid > 0) {
					$capitalsource = CallServer::getCapitalsourceById( $capitalsourceid );
					if ($capitalsource) {
						$all_data = parent::map( $capitalsource );
						$all_data ['statecomment'] = $this->coreDomains->get_domain_meaning( 'CAPITALSOURCE_STATE', $all_data ['state'] );
						$all_data ['typecomment'] = $this->coreDomains->get_domain_meaning( 'CAPITALSOURCE_TYPE', $all_data ['type'] );
						$this->template->assign( 'ALL_DATA', $all_data );
					}
				}
				break;
		}

		$this->template->assign( 'ERRORS', $this->get_errors() );

		$this->parse_header( 1 );
		return $this->fetch_template( 'display_delete_capitalsource.tpl' );
	}
}
?>
