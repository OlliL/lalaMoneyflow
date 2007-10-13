<?php
#-
# Copyright (c) 2005-2007 Oliver Lehmann <oliver@FreeBSD.org>
# All rights reserved.
#
# Redistribution and use in source and binary forms, with or without
# modification, are permitted provided that the following conditions
# are met:
# 1. Redistributions of source code must retain the above copyright
#	notice, this list of conditions and the following disclaimer
# 2. Redistributions in binary form must reproduce the above copyright
#	notice, this list of conditions and the following disclaimer in the
#	documentation and/or other materials provided with the distribution.
#
# THIS SOFTWARE IS PROVIDED BY THE AUTHOR AND CONTRIBUTORS ``AS IS'' AND
# ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
# IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
# ARE DISCLAIMED.  IN NO EVENT SHALL THE AUTHOR OR CONTRIBUTORS BE LIABLE
# FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
# DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS
# OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION)
# HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
# LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY
# OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF
# SUCH DAMAGE.
#
# $Id: moduleLanguages.php,v 1.1 2007/10/13 19:53:44 olivleh1 Exp $
#

require_once 'module/module.php';
require_once 'core/coreLanguages.php';
require_once 'core/coreText.php';

class moduleLanguages extends module {

	function moduleLanguages() {
		$this->module();
		$this->coreLanguages  = new coreLanguages();
		$this->coreText  = new coreText();
	}

	function display_list_languages( $letter ) {

		$all_index_letters = $this->coreLanguages->get_all_index_letters();
		$num_languages = $this->coreLanguages->count_all_data();
		
		if( empty( $letter ) && $num_languages < $this->coreTemplates->get_max_rows() ) {
			$letter = 'all';
		}
		
		if( $letter == 'all' ) {
			$all_data = $this->coreLanguages->get_all_data();
		} elseif( !empty( $letter ) ) {
			$all_data = $this->coreLanguages->get_all_matched_data( $letter );
		} else {
			$all_data = array();
		}
		
		$this->template->assign( 'ALL_DATA',          $all_data          );
		$this->template->assign( 'COUNT_ALL_DATA',    count( $all_data ) );
		$this->template->assign( 'ALL_INDEX_LETTERS', $all_index_letters );

		$this->parse_header();
		return $this->fetch_template( 'display_list_languages.tpl' );
	}

	function display_edit_language( $realaction, $id, $all_data ) {

		if( !$id ) {
			return ' ';
		}

		switch( $realaction ) {
			case 'save':
			
				foreach( $all_data as $textid => $data ) {
					if( $data['text'] != $data['orig_text'] ) {
						$this->coreText->update_text( $textid, $id, $data['text'] );
					}
				}

			default:
				$all_data     = $this->coreText->get_lang_data( $id );
				$all_data_eng = $this->coreText->get_lang_data( 1 );
				$lang_eng     = $this->coreLanguages->get_language( 1 );
				$lang         = $this->coreLanguages->get_language( $id );

				$this->template->assign( 'LANGUAGEID',   $id           );
				$this->template->assign( 'LANG',         $lang         );
				$this->template->assign( 'LANG_ENG',     $lang_eng     );
				$this->template->assign( 'ALL_DATA',     $all_data     );
				$this->template->assign( 'ALL_DATA_ENG', $all_data_eng );
				break;
		}

		$this->template->assign( 'ERRORS', $this->get_errors() );

		$this->parse_header();
		return $this->fetch_template( 'display_edit_language.tpl' );
	}

	function display_add_language( $realaction, $all_data ) {


		switch( $realaction ) {
			case 'save':
			
				$ret = $this->coreLanguages->add_language( $all_data['language'], $all_data['source'] );
			
				if( $ret ) {
					$this->template->assign( 'CLOSE',    1 );
					break;
				}				

			default:
				break;
		}

		$this->template->assign( 'ERRORS', $this->get_errors() );
		$this->template->assign( 'LANGUAGE_VALUES', $this->coreLanguages->get_all_data() );

		$this->parse_header( 1 );
		return $this->fetch_template( 'display_add_language.tpl' );
	}

}
?>
