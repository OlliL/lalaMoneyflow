<?php

/*
	$Id: moduleCapitalSources.php,v 1.5 2005/03/09 20:20:51 olivleh1 Exp $
*/

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

		$this->template->assign( 'ERRORS', $this->get_errors() );

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

		$this->template->assign( 'ERRORS', $this->get_errors() );

		$this->parse_header( 1 );
		return $this->template->fetch( './display_delete_capitalsource.tpl' );
	}
}
?>
