<?php
//
// Copyright (c) 2007-2015 Oliver Lehmann <lehmann@ans-netz.de>
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
// $Id: moduleLanguages.php,v 1.17 2015/02/13 00:03:37 olivleh1 Exp $
//
namespace client\module;

use client\core\coreLanguages;
use client\core\coreText;

class moduleLanguages extends module {
	private $coreLanguages;
	private $coreText;

	public final function __construct() {
		parent::__construct();
		$this->coreLanguages = new coreLanguages();
		$this->coreText = new coreText();
	}

	public final function display_list_languages($letter) {
		$all_index_letters = $this->coreLanguages->get_all_index_letters();

		if (empty( $letter )) {
			$letter = 'all';
		}

		if ($letter == 'all') {
			$all_data = $this->coreLanguages->get_all_data();
		} elseif (! empty( $letter )) {
			$all_data = $this->coreLanguages->get_all_matched_data( $letter );
		} else {
			$all_data = array ();
		}

		$this->template_assign( 'ALL_DATA', $all_data );
		$this->template_assign( 'COUNT_ALL_DATA', count( $all_data ) );
		$this->template_assign( 'ALL_INDEX_LETTERS', $all_index_letters );

		$this->parse_header();
		return $this->fetch_template( 'display_list_languages.tpl' );
	}

	public final function display_edit_language($realaction, $id, $all_data) {
		if (! $id) {
			return ' ';
		}

		switch ($realaction) {
			case 'save' :

				foreach ( $all_data as $textid => $data ) {
					if ($data ['text'] != $data ['orig_text']) {
						$this->coreText->update_text( $textid, $id, $data ['text'] );
					}
				}

			default :
				$all_data = $this->coreText->get_lang_data( $id );
				foreach ( $all_data as $textid => $data ) {
					$all_data[$textid] = html_entity_decode( $data, ENT_COMPAT | ENT_HTML401 );
				}
				$lang = $this->coreLanguages->get_language_name( $id );

				$all_data_eng = $this->coreText->get_lang_data( 1 );
				$lang_eng = $this->coreLanguages->get_language_name( 1 );

				$this->template_assign( 'LANGUAGEID', $id );
				$this->template_assign( 'LANG', $lang );
				$this->template_assign( 'LANG_ENG', $lang_eng );
				$this->template_assign( 'ALL_DATA', $all_data );
				$this->template_assign( 'ALL_DATA_ENG', $all_data_eng );
				break;
		}

		$this->template_assign( 'ERRORS', $this->get_errors() );

		$this->parse_header();
		return $this->fetch_template( 'display_edit_language.tpl' );
	}

	public final function display_add_language($realaction, $all_data) {
		switch ($realaction) {
			case 'save' :

				$languageId = $this->coreLanguages->add_language( $all_data ['language'] );

				if ($languageId > 0) {
					$this->coreText->create_new_textfile( $all_data ['source'], $languageId );
					$this->template_assign( 'CLOSE', 1 );
					break;
				}

			default :
				break;
		}

		$this->template_assign( 'ERRORS', $this->get_errors() );
		$this->template_assign( 'LANGUAGE_VALUES', $this->coreLanguages->get_all_data() );

		$this->parse_header( 1 );
		return $this->fetch_template( 'display_add_language.tpl' );
	}
}
?>
