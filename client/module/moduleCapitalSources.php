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
// $Id: moduleCapitalSources.php,v 1.59 2015/08/14 21:02:55 olivleh1 Exp $
//
namespace client\module;

use client\handler\CapitalsourceControllerHandler;
use client\core\coreText;
use client\util\Environment;

class moduleCapitalSources extends module {
	private $coreText;

	public final function __construct() {
		parent::__construct();
		$this->coreText = new coreText();
	}

	public final function display_list_capitalsources($letter, $currently_valid) {
		$listCapitalsources = CapitalsourceControllerHandler::getInstance()->showCapitalsourceList( $letter, $currently_valid );

		$all_index_letters = $listCapitalsources ['initials'];
		$all_data = $listCapitalsources ['capitalsources'];
		$currently_valid = $listCapitalsources ['currently_valid'];

		foreach ( $all_data as $key => $data ) {
			$all_data [$key] ['statecomment'] = $this->coreText->get_domain_meaning( 'CAPITALSOURCE_STATE', $data ['state'] );
			$all_data [$key] ['typecomment'] = $this->coreText->get_domain_meaning( 'CAPITALSOURCE_TYPE', $data ['type'] );
			if ($data ['mur_userid'] == Environment::getInstance()->getUserId()) {
				$all_data [$key] ['owner'] = true;
			} else {
				$all_data [$key] ['owner'] = false;
			}
		}
		$this->template_assign( 'ALL_DATA', $all_data );
		$this->template_assign( 'COUNT_ALL_DATA', count( $all_data ) );
		$this->template_assign( 'LETTER', $letter );
		$this->template_assign( 'ALL_INDEX_LETTERS', $all_index_letters );
		$this->template_assign( 'CURRENTLY_VALID', $currently_valid );

		$this->parse_header_without_embedded( 0, 'display_list_capitalsources_bs.tpl' );
		return $this->fetch_template( 'display_list_capitalsources_bs.tpl' );
	}

	public final function display_edit_capitalsource($capitalsourceid) {
		if ($capitalsourceid > 0) {
			$all_data = CapitalsourceControllerHandler::getInstance()->showEditCapitalsource( $capitalsourceid );
		} else {
			$all_data = array ();
		}

		$type_values = $this->coreText->get_domain_data( 'CAPITALSOURCE_TYPE' );
		$state_values = $this->coreText->get_domain_data( 'CAPITALSOURCE_STATE' );

		$this->template_assign( 'TYPE_VALUES', $type_values );
		$this->template_assign( 'STATE_VALUES', $state_values );
		$this->template_assign( 'CAPITALSOURCEID', $capitalsourceid );
		$this->template_assign_raw( 'JSON_FORM_DEFAULTS', json_encode( $all_data ) );

		$this->parse_header_without_embedded( 1, 'display_edit_capitalsource_bs.tpl' );
		return $this->fetch_template( 'display_edit_capitalsource_bs.tpl' );
	}

	public final function edit_capitalsource($capitalsourceid, $all_data) {
		$all_data ['capitalsourceid'] = $capitalsourceid;

		if ($capitalsourceid == 0)
			$ret = CapitalsourceControllerHandler::getInstance()->createCapitalsource( $all_data );
		else
			$ret = CapitalsourceControllerHandler::getInstance()->updateCapitalsource( $all_data );

		return $this->handleReturnForAjax( $ret );
	}

	public final function display_delete_capitalsource($capitalsourceid) {
		$all_data = CapitalsourceControllerHandler::getInstance()->showDeleteCapitalsource( $capitalsourceid );
		if (is_array( $all_data )) {
			$all_data ['statecomment'] = $this->coreText->get_domain_meaning( 'CAPITALSOURCE_STATE', $all_data ['state'] );
			$all_data ['typecomment'] = $this->coreText->get_domain_meaning( 'CAPITALSOURCE_TYPE', $all_data ['type'] );
			$this->template_assign_raw( 'JSON_FORM_DEFAULTS', json_encode( $all_data ) );
		}

		$this->parse_header_without_embedded( 1, 'display_delete_capitalsource_bs.tpl' );
		return $this->fetch_template( 'display_delete_capitalsource_bs.tpl' );
	}

	public final function delete_capitalsource($capitalsourceid) {
		$ret = CapitalsourceControllerHandler::getInstance()->deleteCapitalsourceById( $capitalsourceid );
		return $this->handleReturnForAjax( $ret );
	}
}
?>
