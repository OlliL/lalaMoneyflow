<?php
#-
# Copyright (c) 2006 Oliver Lehmann <oliver@FreeBSD.org>
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
# $Id: moduleCurrencies.php,v 1.4 2007/07/24 18:22:08 olivleh1 Exp $
#

require_once 'module/module.php';
require_once 'core/coreCurrencies.php';

class moduleCurrencies extends module {

	function moduleCurrencies() {
		$this->module();
		$this->coreCurrencies = new coreCurrencies();
	}

	function display_list_currencies( $letter ) {

		$all_index_letters = $this->coreCurrencies->get_all_index_letters();
		$num_currencies = $this->coreCurrencies->count_all_data();
		
		if( empty($letter) && $num_currencies < MAX_ROWS ) {
			$letter = 'all';
		}
		
		if( $letter == 'all') {
			$all_data=$this->coreCurrencies->get_all_data();
		} elseif( !empty( $letter ) ) {
			$all_data=$this->coreCurrencies->get_all_matched_data( $letter );
		} else {
			$all_data=array();
		}
		
		$this->template->assign( 'ALL_DATA',          $all_data          );
		$this->template->assign( 'COUNT_ALL_DATA',    count( $all_data ) );
		$this->template->assign( 'ALL_INDEX_LETTERS', $all_index_letters );

		$this->parse_header();
		return $this->fetch_template( 'display_list_currencies.tpl' );
	}

	function display_edit_currency( $realaction, $id, $all_data ) {

		switch( $realaction ) {
			case 'save':
				if( $id == 0 ) {
					$ret=$this->coreCurrencies->add_currency( $all_data['currency'], $all_data['att_default'] );
				} else {
					$ret=$this->coreCurrencies->update_currency( $id, $all_data['currency'], $all_data['att_default'] );
				}

				if( $ret ) {
					$this->template->assign( 'CLOSE',    1 );
					break;
				}				
			default:
				if( $id > 0 ) {
					if( !is_array($all_data) ) {
						$all_data=$this->coreCurrencies->get_id_data( $id );
					} else {
						$all_data['currencyid'] = $id;
					}				
				} else {
					$all_data['att_default']=0;
				}
				$this->template->assign( 'ALL_DATA', $all_data );
				break;
		}

		$this->template->assign( 'ERRORS', $this->get_errors() );

		$this->parse_header( 1 );
		return $this->fetch_template( 'display_edit_currencies.tpl' );
	}

	function display_delete_currency( $realaction, $id ) {

		switch( $realaction ) {
			case 'yes':
				if( $this->coreCurrencies->delete_currency( $id ) ) {
					$this->template->assign( 'CLOSE', 1 );
					break;
				}
			default:
				$all_data=$this->coreCurrencies->get_id_data( $id );
				$this->template->assign( 'ALL_DATA', $all_data );
				break;
		}

		$this->template->assign( 'ERRORS', $this->get_errors() );

		$this->parse_header( 1 );
		return $this->fetch_template( 'display_delete_currencies.tpl' );
	}
}
?>
