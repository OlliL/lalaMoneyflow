<?php
#-
# Copyright (c) 2005-2006 Oliver Lehmann <oliver@FreeBSD.org>
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
# $Id: moduleCapitalSources.php,v 1.8 2006/12/19 12:54:13 olivleh1 Exp $
#

require_once 'module/module.php';
require_once 'core/coreCapitalSources.php';

class moduleCapitalSources extends module {

	function moduleCapitalSources() {
		$this->module();
		$this->coreCapitalSources=new coreCapitalSources();
	}

	function display_list_capitalsources( $letter ) {

		$all_index_letters=$this->coreCapitalSources->get_all_index_letters();

		if( $letter == 'all' ) {
			$all_data=$this->coreCapitalSources->get_all_data();
		} elseif( !empty( $letter ) ) {
			$all_data=$this->coreCapitalSources->get_all_matched_data( $letter );
		} else {
			$all_data=array();
		}

		$this->template->assign( 'ALL_DATA',          $all_data          );
		$this->template->assign( 'COUNT_ALL_DATA',    count( $all_data ) );
		$this->template->assign( 'ALL_INDEX_LETTERS', $all_index_letters );

		$this->parse_header();
		return $this->template->fetch( './display_list_capitalsources.tpl' );
	}

	function display_edit_capitalsource( $realaction, $id, $all_data ) {

		switch( $realaction ) {
			case 'save':
				if( $id == 0 )
					$ret=$this->coreCapitalSources->add_capitalsource( $all_data['type'], $all_data['state'], $all_data['accountnumber'], $all_data['bankcode'], $all_data['comment'], $all_data['validfrom'], $all_data['validtil'] );
				else
					$ret=$this->coreCapitalSources->update_capitalsource( $id, $all_data['type'], $all_data['state'], $all_data['accountnumber'], $all_data['bankcode'], $all_data['comment'], $all_data['validfrom'], $all_data['validtil'] );

				if( $ret ) {
					$this->template->assign( 'CLOSE', 1 );
					break;
				}
			default:
				if( $id > 0 ) {
					$all_data=$this->coreCapitalSources->get_id_data( $id );
					$this->template->assign( 'ALL_DATA', $all_data );
				}
				$type_values=$this->coreCapitalSources->get_enum_type();
				$state_values=$this->coreCapitalSources->get_enum_state();

				$this->template->assign( 'TYPE_VALUES',  $type_values  );
				$this->template->assign( 'STATE_VALUES', $state_values );
				break;
		}

		$this->template->assign( 'ERRORS', get_errors() );

		$this->parse_header( 1 );
		return $this->template->fetch( './display_edit_capitalsource.tpl' );
	}

	function display_delete_capitalsource( $realaction, $id ) {

		switch( $realaction ) {
			case 'yes':
				if( $this->coreCapitalSources->delete_capitalsource( $id ) ) {
					$this->template->assign( 'CLOSE', 1 );
					break;
				}
			default:
				$all_data=$this->coreCapitalSources->get_id_data( $id );
				$this->template->assign( 'ALL_DATA', $all_data );
				break;
		}

		$this->template->assign( 'ERRORS', get_errors() );

		$this->parse_header( 1 );
		return $this->template->fetch( './display_delete_capitalsource.tpl' );
	}
}
?>
