<?php

/*
	$Id: moduleContractPartners.php,v 1.1 2005/03/04 23:52:09 olivleh1 Exp $
*/

require_once 'module/module.php';
require_once 'core/coreContractPartners.php';

class moduleContractPartners extends module {

	function moduleContractPartners() {
		$this->module();
		$this->coreContractPartners=new coreContractPartners();
	}

	function display_list_contractpartners( $letter ) {

		$all_index_letters=$this->coreContractPartners->get_all_index_letters();

		if( $letter == 'all' ) {
			$all_data=$this->coreContractPartners->get_all_data();
		} elseif( !empty( $letter ) ) {
			$all_data=$this->coreContractPartners->get_all_matched_data( $letter );
		}

		$this->template->assign( 'ALL_DATA',          $all_data          );
		$this->template->assign( 'COUNT_ALL_DATA',    count($all_data)   );
		$this->template->assign( 'ALL_INDEX_LETTERS', $all_index_letters );
		
		$this->parse_header();
		return $this->template->fetch( './display_list_contractpartners.tpl' );
	}

	function display_edit_contractpartner( $realaction, $id, $all_data ) {

		switch( $realaction ) {
			case 'save':
				if( $id == 0 )
					$ret=$this->coreContractPartners->add_contractpartner( $all_data['name'], $all_data['street'], $all_data['postcode'], $all_data['town'], $all_data['country'] );
				else
					$ret=$this->coreContractPartners->update_contractpartner( $id, $all_data['name'], $all_data['street'], $all_data['postcode'], $all_data['town'], $all_data['country'] );

				if( $ret )
					$this->template->assign( 'CLOSE', 1 );
				break;
			default:
				if( $id > 0 ) {
					$all_data=$this->coreContractPartners->get_id_data( $id );
					$this->template->assign( 'ALL_DATA', $all_data );
				}
				break;
		}

		$this->template->assign( 'ERRORS', $this->get_errors() );
		
		$this->parse_header(1);
		return $this->template->fetch( './display_edit_contractpartner.tpl' );
	}

	function display_delete_contractpartner( $realaction, $id ) {

		switch( $realaction ) {
			case 'yes':
				if( $this->coreContractPartners->delete_contractpartner( $id ) ) {
					$this->template->assign( 'CLOSE', 1 );
				} else {
					$all_data=$this->coreContractPartners->get_id_data( $id );
					$this->template->assign( 'ALL_DATA', $all_data );
				}
				break;
			default:
				$all_data=$this->coreContractPartners->get_id_data( $id );
				$this->template->assign( 'ALL_DATA', $all_data );
				break;
		}

		$this->template->assign( 'ERRORS', $this->get_errors() );
		
		$this->parse_header(1);
		return $this->template->fetch( './display_delete_contractpartner.tpl' );
	}
}
?>
