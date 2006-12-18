<?php

/*
	$Id: modulePreDefMoneyFlows.php,v 1.11 2006/12/18 12:24:12 olivleh1 Exp $
*/

require_once 'module/module.php';
require_once 'core/coreCapitalSources.php';
require_once 'core/coreContractPartners.php';
require_once 'core/coreCurrencies.php';
require_once 'core/corePreDefMoneyFlows.php';

class modulePreDefMoneyFlows extends module {

	function modulePreDefMoneyFlows() {
		$this->module();
		$this->coreCapitalSources=new coreCapitalSources();
		$this->coreContractPartners=new coreContractPartners();
		$this->coreCurrencies=new coreCurrencies();
		$this->corePreDefMoneyFlows=new corePreDefMoneyFlows();
	}


	function display_list_predefmoneyflows( $letter ) {

		$all_index_letters=$this->corePreDefMoneyFlows->get_all_index_letters();

		if( $letter == 'all' ) {
			$all_data=$this->corePreDefMoneyFlows->get_all_data();
		} elseif( !empty( $letter ) ) {
			$all_data=$this->corePreDefMoneyFlows->get_all_matched_data( $letter );
		}

		if( is_array( $all_data ) ) {
			foreach( $all_data as $key => $value ) {
				$all_data[$key]['capitalsource_comment']=$this->coreCapitalSources->get_comment( $all_data[$key]['capitalsourceid'] );
				$all_data[$key]['contractpartner_name']=$this->coreContractPartners->get_name( $all_data[$key]['contractpartnerid'] );
			}
		}

		$this->template->assign( 'ALL_DATA',          $all_data          );
		$this->template->assign( 'COUNT_ALL_DATA',    count( $all_data ) );
		$this->template->assign( 'ALL_INDEX_LETTERS', $all_index_letters );
		$this->template->assign( 'CURRENCY',          $this->coreCurrencies->get_displayed_currency() );

		$this->parse_header();
		return $this->template->fetch( './display_list_predefmoneyflows.tpl' );
	}

	function display_edit_predefmoneyflow( $realaction, $id, $all_data ) {

		switch( $realaction ) {
			case 'save':
				if( $id == 0 )
					$ret=$this->corePreDefMoneyFlows->add_predefmoneyflow( $all_data['amount'], $all_data['capitalsourceid'], $all_data['contractpartnerid'], $all_data['comment'] );
				else
					$ret=$this->corePreDefMoneyFlows->update_predefmoneyflow( $id, $all_data['amount'], $all_data['capitalsourceid'], $all_data['contractpartnerid'], $all_data['comment'] );

				if( $ret ) {
					$this->template->assign( 'CLOSE', 1 );
					break;
				}
			default:
				if( $id > 0 ) {
					$all_data=$this->corePreDefMoneyFlows->get_id_data( $id );
					$this->template->assign( 'ALL_DATA', $all_data );
					$capitalsourceid=$this->corePreDefMoneyFlows->get_capitalsourceid( $id );
					if ( $this->coreCapitalSources->id_is_valid( $capitalsourceid, date( 'Y-m-d' ) ) ) {
						$capitalsource_values=$this->coreCapitalSources->get_valid_comments( date( 'Y-m-d' ), date( 'Y-m-d' ) );
					} else {
						$capitalsource_values=$this->coreCapitalSources->get_all_comments();
					}
				} else {
					$capitalsource_values=$this->coreCapitalSources->get_valid_comments( date( 'Y-m-d' ), date( 'Y-m-d' ) );
				}				

				$contractpartner_values=$this->coreContractPartners->get_all_names();

				$this->template->assign( 'CAPITALSOURCE_VALUES',   $capitalsource_values   );
				$this->template->assign( 'CONTRACTPARTNER_VALUES', $contractpartner_values );
				break;
		}

		$this->template->assign( 'CURRENCY', $this->coreCurrencies->get_displayed_currency() );
		$this->template->assign( 'ERRORS',   get_errors() );

		$this->parse_header( 1 );
		return $this->template->fetch( './display_edit_predefmoneyflow.tpl' );
	}

	function display_delete_predefmoneyflow( $realaction, $id ) {

		switch( $realaction ) {
			case 'yes':
				if( $this->corePreDefMoneyFlows->delete_predefmoneyflow( $id ) ) {
					$this->template->assign( 'CLOSE', 1 );
					break;
				}

			default:
				$all_data=$this->corePreDefMoneyFlows->get_id_data( $id );
				$all_data['capitalsource_comment']=$this->coreCapitalSources->get_comment( $all_data['capitalsourceid'] );
				$all_data['contractpartner_name']=$this->coreContractPartners->get_name( $all_data['contractpartnerid'] );
				$this->template->assign( 'ALL_DATA', $all_data );
				break;
		}

		$this->template->assign( 'CURRENCY', $this->coreCurrencies->get_displayed_currency() );
		$this->template->assign( 'ERRORS',   get_errors() );

		$this->parse_header( 1 );
		return $this->template->fetch( './display_delete_predefmoneyflow.tpl' );
	}
}
?>
