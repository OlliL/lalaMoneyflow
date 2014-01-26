<?php
#-
# Copyright (c) 2006-2014 Oliver Lehmann <oliver@FreeBSD.org>
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
# $Id: moduleGroups.php,v 1.4 2014/01/26 12:24:48 olivleh1 Exp $
#

require_once 'module/module.php';
require_once 'core/coreSession.php';
require_once 'core/coreGroups.php';

class moduleGroups extends module {

	function moduleGroups() {
		parent::__construct();
		$this->coreSession = new coreSession();
		$this->coreGroups = new coreGroups();
	}

	function display_list_groups( $letter ) {

		$all_index_letters = $this->coreGroups->get_all_index_letters();
		$num_groups = $this->coreGroups->count_all_data();
		
		if( empty( $letter ) && $num_groups < $this->coreTemplates->get_max_rows() ) {
			$letter = 'all';
		}
		
		if( $letter == 'all' ) {
			$all_data = $this->coreGroups->get_all_data();
		} elseif( !empty( $letter ) ) {
			$all_data = $this->coreGroups->get_all_matched_data( $letter );
		} else {
			$all_data = array();
		}
		
		$this->template->assign( 'ALL_DATA',          $all_data          );
		$this->template->assign( 'COUNT_ALL_DATA',    count( $all_data ) );
		$this->template->assign( 'ALL_INDEX_LETTERS', $all_index_letters );

		$this->parse_header();
		return $this->fetch_template( 'display_list_groups.tpl' );
	}

	function display_edit_group( $realaction, $id, $all_data ) {

		switch( $realaction ) {
			case 'save':
				if( $id == 0 ) {
					$ret = $this->coreGroups->add_group( $all_data['name'] );
				} else {
					$ret = $this->coreGroups->update_group( $id, $all_data['name'] );
				}

				if( $ret ) {
					$this->template->assign( 'CLOSE',    1 );
				} else {
					$this->template->assign( 'ALL_DATA', $all_data );
				}				
				break;
			default:
				if( $id > 0 ) {
					$all_data = $this->coreGroups->get_id_data( $id );
				}
				$this->template->assign( 'ALL_DATA', $all_data );
				break;
		}

		$this->template->assign( 'ERRORS', $this->get_errors() );

		$this->parse_header( 1 );
		return $this->fetch_template( 'display_edit_group.tpl' );
	}

	function display_delete_group( $realaction, $id, $force ) {

		switch( $realaction ) {
			case 'yes':
				if( empty( $force ) && $this->coreGroups->group_is_assigned( $id ) ) {
					add_error( 211 );
					$this->template->assign( 'ASK', 1 );
				} else {
					if( $this->coreGroups->delete_group( $id ) ) {
						$this->template->assign( 'CLOSE', 1 );
						break;
					} else {
						add_error( 217 );
					}
				}
			default:
				$all_data = $this->coreGroups->get_id_data( $id );
				$this->template->assign( 'ALL_DATA', $all_data );
				break;
		}

		$this->template->assign( 'ERRORS', $this->get_errors() );

		$this->parse_header( 1 );
		return $this->fetch_template( 'display_delete_group.tpl' );
	}
}
?>
