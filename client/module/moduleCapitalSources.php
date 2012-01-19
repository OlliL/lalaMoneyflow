<?php
#-
# Copyright (c) 2005-2012 Oliver Lehmann <oliver@FreeBSD.org>
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
# $Id: moduleCapitalSources.php,v 1.22 2012/01/19 21:25:10 olivleh1 Exp $
#

require_once 'module/module.php';
require_once 'core/coreCapitalSources.php';
require_once 'core/coreSettings.php';

class moduleCapitalSources extends module {

	function moduleCapitalSources() {
		$this->module();
		$this->coreCapitalSources = new coreCapitalSources();
		$this->coreSettings       = new coreSettings();

		$date_format = $this->coreSettings->get_date_format( USERID );
		$this->date_format = $date_format['dateformat'];
	}

	function display_list_capitalsources( $letter ) {

		$all_index_letters = $this->coreCapitalSources->get_all_index_letters();
		$num_sources       = $this->coreCapitalSources->count_all_data();
		
		if( empty( $letter ) && $num_sources < $this->coreTemplates->get_max_rows() ) {
			$letter = 'all';
		}

		if( $letter == 'all' ) {
			$all_data = $this->coreCapitalSources->get_editable_data();
		} elseif( !empty( $letter ) ) {
			$all_data = $this->coreCapitalSources->get_all_matched_data( $letter );
		} else {
			$all_data = array();
		}

		foreach( $all_data as $key => $value ) {
			$all_data[$key]['validfrom'] = convert_date_to_gui( $all_data[$key]['validfrom'], $this->date_format );
			$all_data[$key]['validtil']  = convert_date_to_gui( $all_data[$key]['validtil'],  $this->date_format );
			if ($all_data[$key]['mur_userid'] == USERID ) {
				$all_data[$key]['owner'] = true;
			} else {
				$all_data[$key]['owner'] = false;
			}
		}


		$this->template->assign( 'ALL_DATA',          $all_data          );
		$this->template->assign( 'COUNT_ALL_DATA',    count( $all_data ) );
		$this->template->assign( 'ALL_INDEX_LETTERS', $all_index_letters );

		$this->parse_header();
		return $this->fetch_template( 'display_list_capitalsources.tpl' );
	}

	function display_edit_capitalsource( $realaction, $capitalsourceid, $all_data ) {

		$validfrom_orig = $all_data['validfrom'];
		$validtil_orig  = $all_data['validtil'];

		switch( $realaction ) {
			case 'save':;
				$all_data['validfrom'] = convert_date_to_db( $all_data['validfrom'], $this->date_format );
				$all_data['validtil']  = convert_date_to_db( $all_data['validtil'],  $this->date_format );
				$valid_data = true;

				if( $all_data['validfrom'] === false ) {
					add_error( 147, array( $this->date_format ) );
					$all_data['validfrom']       = $validfrom_orig;
					$all_data['validfrom_error'] = 1;
					$valid_data = false;
				}
				if( $all_data['validtil'] === false ) {
					add_error( 147, array( $this->date_format ) );
					$all_data['validtil']       = $validtil_orig;
					$all_data['validtil_error'] = 1;
					$valid_data = false;
				}

				if( $valid_data === true ) {
					if( $capitalsourceid == 0 )
						$ret = $this->coreCapitalSources->add_capitalsource( $all_data['type'], $all_data['state'], $all_data['accountnumber'], $all_data['bankcode'], $all_data['comment'], $all_data['validfrom'], $all_data['validtil'], $all_data['att_group_use'] );
					else
						$ret = $this->coreCapitalSources->update_capitalsource( $capitalsourceid, $all_data['type'], $all_data['state'], $all_data['accountnumber'], $all_data['bankcode'], $all_data['comment'], $all_data['validfrom'], $all_data['validtil'], $all_data['att_group_use'] );
				}

				if( $ret === true || $ret > 0 ) {
					$this->template->assign( 'CLOSE', 1 );
					break;
				}
			default:
				if( !is_array( $all_data ) ) {
					if( $capitalsourceid > 0 ) {
						$all_data              = $this->coreCapitalSources->get_id_data( $capitalsourceid );
					} else {
						$all_data['validfrom'] = date( 'Y-m-d', time() );
						$all_data['validtil']  = MAX_YEAR;
					}
				}
				$type_values  = $this->coreCapitalSources->get_enum_type();
				$state_values = $this->coreCapitalSources->get_enum_state();

				$this->template->assign( 'TYPE_VALUES',  $type_values  );
				$this->template->assign( 'STATE_VALUES', $state_values );
				break;
		}

		if( empty( $all_data['validfrom_error'] ) )
			$all_data['validfrom'] = convert_date_to_gui( $all_data['validfrom'],  $this->date_format );
		if( empty( $all_data['validtil_error'] ) )
			$all_data['validtil']  = convert_date_to_gui( $all_data['validtil'],   $this->date_format );

		$this->template->assign( 'ALL_DATA',        $all_data           );
		$this->template->assign( 'CAPITALSOURCEID', $capitalsourceid    );
		$this->template->assign( 'ERRORS',          $this->get_errors() );

		$this->parse_header( 1 );
		return $this->fetch_template( 'display_edit_capitalsource.tpl' );
	}

	function display_delete_capitalsource( $realaction, $capitalsourceid ) {

		switch( $realaction ) {
			case 'yes':
				if( $this->coreCapitalSources->delete_capitalsource( $capitalsourceid ) ) {
					$this->template->assign( 'CLOSE', 1 );
					break;
				}
			default:
				$all_data              = $this->coreCapitalSources->get_id_data( $capitalsourceid );
				$all_data['validfrom'] = convert_date_to_gui( $all_data['validfrom'], $this->date_format );
				$all_data['validtil']  = convert_date_to_gui( $all_data['validtil'],  $this->date_format );
				$this->template->assign( 'ALL_DATA', $all_data );
				break;
		}

		$this->template->assign( 'ERRORS', $this->get_errors() );

		$this->parse_header( 1 );
		return $this->fetch_template( 'display_delete_capitalsource.tpl' );
	}
}
?>
