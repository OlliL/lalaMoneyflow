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
# $Id: moduleContractPartners.php,v 1.15 2012/01/19 21:25:10 olivleh1 Exp $
#

require_once 'module/module.php';
require_once 'core/coreContractPartners.php';

class moduleContractPartners extends module {

	function moduleContractPartners() {
		$this->module();
		$this->coreContractPartners=new coreContractPartners();
	}

	function display_list_contractpartners( $letter ) {

		$all_index_letters=$this->coreContractPartners->get_all_index_letters();
		$num_partners = $this->coreContractPartners->count_all_data();
		
		if( empty($letter) && $num_partners < $this->coreTemplates->get_max_rows() ) {
			$letter = 'all';
		}

		if( $letter == 'all' ) {
			$all_data=$this->coreContractPartners->get_all_data();
		} elseif( !empty( $letter ) ) {
			$all_data=$this->coreContractPartners->get_all_matched_data( $letter );
		} else {
			$all_data=array();
		}

		$this->template->assign( 'ALL_DATA',          $all_data          );
		$this->template->assign( 'COUNT_ALL_DATA',    count( $all_data ) );
		$this->template->assign( 'ALL_INDEX_LETTERS', $all_index_letters );

		$this->parse_header();
		return $this->fetch_template( 'display_list_contractpartners.tpl' );
	}

	function display_edit_contractpartner( $realaction, $id, $all_data ) {

		switch( $realaction ) {
			case 'save':
				if( $id == 0 )
					$ret=$this->coreContractPartners->add_contractpartner( $all_data['name'], $all_data['street'], $all_data['postcode'], $all_data['town'], $all_data['country'] );
				else
					$ret=$this->coreContractPartners->update_contractpartner( $id, $all_data['name'], $all_data['street'], $all_data['postcode'], $all_data['town'], $all_data['country'] );

				if( $ret ) {
					$this->template->assign( 'CLOSE',    1 );
				} else {
					$this->template->assign( 'ALL_DATA', $all_data );
				}				
				break;
			default:
				if( $id > 0 ) {
					$all_data=$this->coreContractPartners->get_id_data( $id );
					$this->template->assign( 'ALL_DATA', $all_data );
				}
				break;
		}

		$this->template->assign( 'ERRORS', $this->get_errors() );

		$this->parse_header( 1 );
		return $this->fetch_template( 'display_edit_contractpartner.tpl' );
	}

	function display_delete_contractpartner( $realaction, $id ) {

		switch( $realaction ) {
			case 'yes':
				if( $this->coreContractPartners->delete_contractpartner( $id ) ) {
					$this->template->assign( 'CLOSE', 1 );
					break;
				}
			default:
				$all_data=$this->coreContractPartners->get_id_data( $id );
				$this->template->assign( 'ALL_DATA', $all_data );
				break;
		}

		$this->template->assign( 'ERRORS', $this->get_errors() );

		$this->parse_header( 1 );
		return $this->fetch_template( 'display_delete_contractpartner.tpl' );
	}
}
?>
