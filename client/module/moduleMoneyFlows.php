<?php

/*
	$Id: moduleMoneyFlows.php,v 1.17 2006/09/21 08:18:13 olivleh1 Exp $
*/

require_once 'module/module.php';
require_once 'core/coreCapitalSources.php';
require_once 'core/coreContractPartners.php';
require_once 'core/coreMoneyFlows.php';
require_once 'core/corePreDefMoneyFlows.php';

class moduleMoneyFlows extends module {

	function moduleMoneyFlows() {
		$this->module();
		$this->coreCapitalSources=new coreCapitalSources();
		$this->coreContractPartners=new coreContractPartners();
		$this->coreMoneyFlows=new coreMoneyFlows();
		$this->corePreDefMoneyFlows=new corePreDefMoneyFlows();
	}

	function display_edit_moneyflow( $realaction, $id, $all_data ) {

		switch( $realaction ) {
			case 'save':
				$ret=$this->coreMoneyFlows->update_moneyflow( $id, $all_data['bookingdate'], $all_data['invoicedate'], $all_data['amount'], $all_data['capitalsourceid'], $all_data['contractpartnerid'], $all_data['comment'] );

				if( $ret ) {
					$this->template->assign( 'CLOSE', 1 );
					break;
				}
			default:
				if( $id > 0 ) {
					$all_data=$this->coreMoneyFlows->get_id_data( $id );
					$this->template->assign( 'ALL_DATA', $all_data );
				}

				$capitalsourceid=$this->coreMoneyFlows->get_capitalsourceid( $id );

				if ( $this->coreCapitalSources->id_is_valid( $capitalsourceid, date( 'Y-m-d' ) ) ) {
					$capitalsource_values=$this->coreCapitalSources->get_valid_comments( date( 'Y-m-d' ), date( 'Y-m-d' ) );
				} else {
					$capitalsource_values=$this->coreCapitalSources->get_all_comments();
				}

				$contractpartner_values=$this->coreContractPartners->get_all_names();

				$this->template->assign( 'CAPITALSOURCE_VALUES',   $capitalsource_values   );
				$this->template->assign( 'CONTRACTPARTNER_VALUES', $contractpartner_values );
				break;
		}

		$this->template->assign( 'ERRORS', get_errors() );

		$this->parse_header( 1 );
		return $this->template->fetch( './display_edit_moneyflow.tpl' );
	}


	function display_add_moneyflow( $realaction, $all_data ) {

		$date=date( 'Y-m-d' );

		switch( $realaction ) {
			case 'save':
				$data_is_valid   = true;
				$nothing_checked = true;
				foreach( $all_data as $id => $value ) {
					if ( $value['checked'] == 1 ) {
						$nothing_checked = false;
						
						if( empty( $value['invoicedate'] ) )
							$value['invoicedate']=$value['bookingdate'];
	
						if( ! is_date( $value['invoicedate'] ) ) {
							add_error( "invoicedate has to be in format YYYY-MM-DD" );
							$all_data[$id]['invoicedate_error'] = 1;
							$data_is_valid = false;
						}

						if( ! is_date( $value['bookingdate'] ) ) {
							add_error( "bookingdate has to be in format YYYY-MM-DD" );
							$all_data[$id]['bookingdate_error'] = 1;
							$data_is_valid = false;
						}
	
						if( empty( $value['comment'] ) ) {
							add_error( "comment can't be empty" );
							$data_is_valid = false;
						}
							
						if( preg_match( '/,/', $value['amount'] ) && preg_match( '/\./', $value['amount'] ) ) {
							add_error( "amount may not contain , or . at the same time, not both" );
							$all_data[$id]['amount_error'] = 1;
							$data_is_valid = false;
						} elseif( preg_match( '/\./', $value['amount'] ) && preg_match_all( '/./', $value['amount'], $foo )  > 1 ) {
							add_error( "amount may not contain one . sign" );
							$all_data[$id]['amount_error'] = 1;
							$data_is_valid = false;
						} elseif( preg_match( '/,/', $value['amount'] ) && preg_match_all( '/,/', $value['amount'], $foo )  > 1 ) {
							add_error( "amount may not contain one , sign" );
							$all_data[$id]['amount_error'] = 1;
							$data_is_valid = false;
						} elseif ( preg_match( '/[^-,\.0-9]/', $value['amount'] ) ) {
							add_error( "amount may only contain numbers and the following signs: - , ." );
							$all_data[$id]['amount_error'] = 1;
							$data_is_valid = false;
						} elseif ( fix_amount($value['amount']) != round( fix_amount($value['amount']), 2 ) ) {
							add_error( "amount may only consist of 2 decimal places" );
							$all_data[$id]['amount_error'] = 1;
							$data_is_valid = false;
						}
					}
				}
				
				if( $nothing_checked ) {
					add_error( "nothing marked to add!" );
					$data_is_valid = false;
				}
				if( $data_is_valid ) {
					foreach( $all_data as $id => $value ) {
						if ( $value['checked'] == 1 ) {
							if( empty( $value['invoicedate'] ) )
								$value['invoicedate']=$value['bookingdate'];
							$ret=$this->coreMoneyFlows->add_moneyflow( $value['bookingdate'], $value['invoicedate'], $value['amount'], $value['capitalsourceid'], $value['contractpartnerid'], $value['comment'] );
						}
					}
				} else {
					break;
				}
			default:
				$all_data_pre=$this->corePreDefMoneyFlows->get_valid_data( $date, $date );

				$all_data[0]=array( 'id'          =>  -1,
				                    'bookingdate' => date( 'Y-m-d' ) );
				$i=1;				
				foreach( $all_data_pre as $key => $value ) {
					$all_data[$i]=$value;
					$all_data[$i]['bookingdate']=$date;
					$all_data[$i]['amount']=sprintf('%.02f',$all_data_pre[$key]['amount']);
					$all_data[$i]['capitalsourcecomment']=$this->coreCapitalSources->get_comment( $all_data_pre[$key]['capitalsourceid'] );
					$all_data[$i]['contractpartnername']=$this->coreContractPartners->get_name( $all_data_pre[$key]['contractpartnerid'] );
					$i++;
				}

				break;
		}
		$capitalsource_values=$this->coreCapitalSources->get_valid_comments( $date, $date );
		$contractpartner_values=$this->coreContractPartners->get_all_names();

		$this->template->assign( 'CAPITALSOURCE_VALUES',   $capitalsource_values   );
		$this->template->assign( 'CONTRACTPARTNER_VALUES', $contractpartner_values );
		$this->template->assign( 'ALL_DATA',               $all_data               );
		$this->template->assign( 'ERRORS', get_errors() );

		$this->parse_header();
		return $this->template->fetch( './display_add_moneyflow.tpl' );
	}


	function display_delete_moneyflow( $realaction, $id ) {

		switch( $realaction ) {
			case 'yes':
				if( $this->coreMoneyFlows->delete_moneyflow( $id ) ) {
					$this->template->assign( 'CLOSE', 1 );
					break;
				}

			default:
				$all_data=$this->coreMoneyFlows->get_id_data( $id );
				$all_data['capitalsource_comment']=$this->coreCapitalSources->get_comment( $all_data['capitalsourceid'] );
				$all_data['contractpartner_name']=$this->coreContractPartners->get_name( $all_data['contractpartnerid'] );
				$this->template->assign( 'ALL_DATA', $all_data );
				break;
		}

		$this->template->assign( 'ERRORS', get_errors() );

		$this->parse_header( 1 );
		return $this->template->fetch( './display_delete_moneyflow.tpl' );
	}
}
?>
