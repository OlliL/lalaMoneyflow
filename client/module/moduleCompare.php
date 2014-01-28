<?php
use rest\client\CallServer;
use rest\base\ErrorCode;
//
// Copyright (c) 2007-2013 Oliver Lehmann <oliver@FreeBSD.org>
// All rights reserved.
//
// Redistribution and use in source and binary forms, with or without
// modification, are permitted provided that the following conditions
// are met:
// 1. Redistributions of source code must retain the above copyright
// notice, this list of conditions and the following disclaimer
// 2. Redistributions in binary form must reproduce the above copyright
// notice, this list of conditions and the following disclaimer in the
// documentation and/or other materials provided with the distribution.
//
// THIS SOFTWARE IS PROVIDED BY THE AUTHOR AND CONTRIBUTORS ``AS IS'' AND
// ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
// IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
// ARE DISCLAIMED. IN NO EVENT SHALL THE AUTHOR OR CONTRIBUTORS BE LIABLE
// FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
// DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS
// OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION)
// HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
// LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY
// OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF
// SUCH DAMAGE.
//
// $Id: moduleCompare.php,v 1.31 2014/01/28 21:14:33 olivleh1 Exp $
//
require_once 'module/module.php';
require_once 'core/coreCompare.php';
require_once 'core/coreMoneyFlows.php';
require_once 'core/coreSettings.php';
require_once 'mapper/CvsFileToCompareDataMapper.php';
require_once 'mapper/CamtFileToCompareDataMapper.php';

class moduleCompare extends module {

	function moduleCompare() {
		parent::__construct();
		$this->coreCompare = new coreCompare();
		$this->coreMoneyFlows = new coreMoneyFlows();
		$this->coreSettings = new coreSettings();
	}

	// TODO - duplicate code
	// filter only the capitalsources which are owned by the user or allowed for group use.
	private function filterCapitalsource($capitalsourceArray) {
		if (is_array( $capitalsourceArray )) {
			foreach ( $capitalsourceArray as $capitalsource ) {
				if ($capitalsource ['att_group_use'] == 1 || $capitalsource ['mur_userid'] == USERID)
					$capitalsource_values [] = $capitalsource;
			}
		}
		return $capitalsource_values;
	}

	private final function getCapitalsourceComment($capitalsourceId) {
		$all_data = CallServer::getInstance()->getCapitalsourceById( $capitalsourceId );
		return $all_data ['comment'];
	}

	private final function getContractpartnerName($contractpartnerId) {
		$all_data = CallServer::getInstance()->getContractpartnerById( $contractpartnerId );
		return $all_data ['name'];
	}

	function display_upload_form($all_data = array()) {
		$showCompareDataForm = CallServer::getInstance()->showCompareDataForm();
		$format_values = $showCompareDataForm ['comparedataformats'];
		$capitalsource_values = $showCompareDataForm ['capitalsources'];

		if (count( $all_data ) === 0) {
			$all_data ['startdate'] = convert_date_to_gui( date( "Y-m-d", mktime( 0, 0, 0, date( 'm', time() ), 1, date( 'Y', time() ) ) ) );
			$all_data ['enddate'] = convert_date_to_gui( date( "Y-m-d", mktime( 0, 0, 0, date( 'm', time() ) + 1, 0, date( 'Y', time() ) ) ) );
			$all_data ['format'] = $this->coreSettings->get_compare_format( USERID );
			$all_data ['mcs_capitalsourceid'] = $this->coreSettings->get_compare_capitalsource( USERID );
		}

		$this->template->assign( 'CAPITALSOURCE_VALUES', $capitalsource_values );
		$this->template->assign( 'FORMAT_VALUES', $format_values );
		$this->template->assign( 'ALL_DATA', $all_data );
		$this->template->assign( 'ERRORS', $this->get_errors() );

		$this->parse_header();
		return $this->fetch_template( 'display_upfrm_cmp_data.tpl' );
	}

	function fill_file_array($data_array, $bookingdate, $invoicedate, $amount, $capitalsourcecomment, $contractpartnername, $comment) {
		if (! is_array( $data_array )) {
			$i = 1;
		} else {
			$i = count( $data_array ) + 1;
		}

		$data_array [$i] ['bookingdate'] = $bookingdate;
		$data_array [$i] ['invoicedate'] = $invoicedate;
		$data_array [$i] ['amount'] = $amount;
		$data_array [$i] ['capitalsourcecomment'] = $capitalsourcecomment;
		$data_array [$i] ['contractpartnername'] = $contractpartnername;
		$data_array [$i] ['comment'] = $comment;

		return ($data_array);
	}

	function fill_db_array($data_array, $bookingdate, $invoicedate, $amount, $capitalsourcecomment, $contractpartnername, $comment, $moneyflowid, $owner) {
		if (! is_array( $data_array )) {
			$i = 1;
		} else {
			$i = count( $data_array ) + 1;
		}

		$data_array [$i] ['bookingdate'] = $bookingdate;
		$data_array [$i] ['invoicedate'] = $invoicedate;
		$data_array [$i] ['amount'] = $amount;
		$data_array [$i] ['capitalsourcecomment'] = $capitalsourcecomment;
		$data_array [$i] ['contractpartnername'] = $contractpartnername;
		$data_array [$i] ['comment'] = $comment;
		$data_array [$i] ['moneyflowid'] = $moneyflowid;
		$data_array [$i] ['owner'] = $owner;

		return ($data_array);
	}

	function display_analyze_form($file, $all_data) {
		return $this->display_analyze_form_alt($file, $all_data);
//  		return $this->display_analyze_form_neu( $file, $all_data );
	}

	function display_analyze_form_neu($file, $all_data) {
		$fileName = $file ['tmp_name'];
		$startDate = $all_data ['startdate'];
		$endDate = $all_data ['enddate'];
		$formatId = $all_data ['format'];
		$capitalSourceId = $all_data ['mcs_capitalsourceid'];

		$valid_data = true;

		if (! dateIsValid( $startDate )) {
			add_error( ErrorCode::DATE_FORMAT_NOT_CORRECT, array (
					GUI_DATE_FORMAT
			) );
			$all_data ['startdate_error'] = 1;
			$valid_data = false;
		}

		if (! dateIsValid( $endDate )) {
			add_error( ErrorCode::DATE_FORMAT_NOT_CORRECT, array (
					GUI_DATE_FORMAT
			) );
			$all_data ['enddate_error'] = 1;
			$valid_data = false;
		}

		if (! $fileName) {
			add_error( ErrorCode::FILEUPLOAD_FAILED );
			$valid_data = false;
		}

		if ($valid_data === false) {
			return $this->display_upload_form( $all_data );
		} else {
			// update the chosen capitalsource and format in the usersettings to remember/reuse the selection next time
			if ($all_data ['mcs_capitalsourceid'] != $this->coreSettings->get_compare_capitalsource( USERID )) {
				$this->coreSettings->set_compare_capitalsource( USERID, $all_data ['mcs_capitalsourceid'] );
			}
			if ($all_data ['format'] != $this->coreSettings->get_compare_format( USERID )) {
				$this->coreSettings->set_compare_format( USERID, $all_data ['format'] );
			}

			$all_data['filecontents'] = file_get_contents( $fileName );

			$result = CallServer::getInstance()->compareData($all_data);
// 			echo base64_decode($result['compareDataRequest']['compareDataTransport']['fileContents']);
print_r($result);
			exit();
		}
	}

	function display_analyze_form_alt($file, $all_data) {
		$startdate = $all_data ['startdate'];
		$enddate = $all_data ['enddate'];
		$valid_data = true;

		if (! empty( $startdate )) {
			$startdate = convert_date_to_db( $startdate );
			if ($startdate === false) {
				add_error( 147, array (
						GUI_DATE_FORMAT
				) );
				$all_data ['startdate_error'] = 1;
				$valid_data = false;
			}
		}

		if (! empty( $enddate )) {
			$enddate = convert_date_to_db( $enddate );
			if ($enddate === false) {
				add_error( 147, array (
						GUI_DATE_FORMAT
				) );
				$all_data ['enddate_error'] = 1;
				$valid_data = false;
			}
		}

		if (! $file ['tmp_name']) {
			add_error( 191 );
			$valid_data = false;
		}

		if ($valid_data === false) {
			return $this->display_upload_form( $all_data );
		} else {

			if ($all_data ['mcs_capitalsourceid'] != $this->coreSettings->get_compare_capitalsource( USERID )) {
				$this->coreSettings->set_compare_capitalsource( USERID, $all_data ['mcs_capitalsourceid'] );
			}
			if ($all_data ['format'] != $this->coreSettings->get_compare_format( USERID )) {
				$this->coreSettings->set_compare_format( USERID, $all_data ['format'] );
			}

			// change given date to timespamp for later "between" comparsion
			$startdate = convert_date_to_timestamp( $all_data ['startdate'] );
			$enddate = convert_date_to_timestamp( $all_data ['enddate'] );

			$compareDataFormats = $this->coreCompare->get_id_data( $all_data ['format'] );

			$compareData = array ();

			if (is_file( $file ['tmp_name'] )) {
				if ($compareDataFormats->getStartline() == 'camt') {
					$camtFileToCompareDataMapper = new CamtFileToCompareDataMapper();
					$compareData = $camtFileToCompareDataMapper->mapCamtFileToCompareData( $file ['tmp_name'] );
				} else {
					$cvsFileToCompareDataMapper = new CvsFileToCompareDataMapper();
					$compareData = $cvsFileToCompareDataMapper->mapCvsFileToCompareData( $file ['tmp_name'], $compareDataFormats );
				}
			}

			if (count( $compareData ) === 0) {
				add_error( 199 );
				return $this->display_upload_form( $all_data );
			}

			$capitalsourcecomment = $this->getCapitalsourceComment( $all_data ['mcs_capitalsourceid'] );

			foreach ( $compareData as $data ) {
				$amount = $data->getAmount();
				$date_stamp = $data->getBookingDate();
				$date_db = convert_timestamp_to_db( $date_stamp );
				$invoicedate = $data->getInvoiceDate();
				$invoicedate_gui = convert_date_to_gui( convert_timestamp_to_db( $invoicedate ) );
				$partner = $data->getPartner();
				$comment = $data->getComment();

				$hitlist = array ();
				$mon_data = array ();

				if ($date_stamp >= $startdate && $date_stamp <= $enddate) {

					$file_array = $this->fill_file_array( $file_array, convert_date_to_gui( $date_db ), $invoicedate_gui, $amount, $capitalsourcecomment, $partner, $comment );
					$file_array_id = count( $file_array );

					// TODO: hier eine Liste von kompletten Moneyflows vom Server zuruecklieferen, dann kann unten das get_id_data weg was nun nur "eigene" Moneyflows findet (geht nicht mehr auf die View)
					$results = $this->coreMoneyFlows->find_single_moneyflow( $date_db, 5, $amount );

					if (is_array( $results )) {
						$result_count = count( $results );

						foreach ( $results as $result ) {
							$moneyflowid = $result ['moneyflowid'];
							if ($moneyflow_used [$moneyflowid] != 1) {
								$moneyflow = CallServer::getInstance()->getMoneyflowById( $moneyflowid );

								$mon_data [$moneyflowid] = $moneyflow;

								$hitlist [$moneyflowid] = 0;

								if ($result_count > 1) {
									// more than one result
									// the program has to try now finding the right result
									// this is done by counting different aspects of a flow and generate a hitlist
									// the result with the highest hitcount is treated as the matching one.

									if ($moneyflow ['bookingdate'] == $date_db)
										$hitlist [$moneyflowid] += 10;

										// if( $moneyflow['invoicedate'] == $date_db )
										// $hitlist[$moneyflowid] += 5;

									if ($moneyflow ['mcs_capitalsourceid'] == $all_data ['mcs_capitalsourceid'])
										$hitlist [$moneyflowid] += 10;

										// does our input-file contain contractpartner information?
									if (! empty( $partner )) {
										$cmp_partner = $partner;
										$mon_partner = $this->getContractpartnerName( $moneyflow ['mcp_contractpartnerid'] );

										$split_pattern = '[\., -]';

										$matching_words = 0;
										$words = 0;
										foreach ( split( $split_pattern, $cmp_partner ) as $cmp_word ) {
											$words ++;
											foreach ( split( $split_pattern, $mon_partner ) as $mon_word ) {
												if (strcasecmp( $mon_word, $cmp_word ) === 0) {
													$hitlist [$moneyflowid] += 10;
													$matching_words ++;
												} elseif (soundex( $mon_word ) == soundex( $cmp_word )) {
													$hitlist [$moneyflowid] += 8;
													$matching_words ++;
												}
											}
										}

										if ($matching_words == $words && $matching_words != 0)
											$hitlist [$moneyflowid] += 5;
									}
								} else {
									$hitlist [$moneyflowid] = 100;
								}
							}
						}
						if (count( $mon_data ) > 0) {
							arsort( $hitlist );
							$moneyflowid = key( $hitlist );
							$moneyflow = $mon_data [$moneyflowid];
							$moneyflow_used [$moneyflowid] = 1;

							$matching_moneyflowids [] = $moneyflowid;

							if ($moneyflow ['mcs_capitalsourceid'] == $all_data ['mcs_capitalsourceid']) {
								$my_capitalsourcecomment = $capitalsourcecomment;
								$diff_capitalsource = false;
							} else {
								$my_capitalsourcecomment = $this->getCapitalsourceComment( $moneyflow ['mcs_capitalsourceid'] );
								$diff_capitalsource = true;
							}

							if ($moneyflow ['mur_userid'] == USERID) {
								$owner = true;
							} else {
								$owner = false;
							}

							$db_array = $this->fill_db_array( $db_array, convert_date_to_gui( $moneyflow ['bookingdate'] ), convert_date_to_gui( $moneyflow ['invoicedate'] ), $moneyflow ['amount'], $my_capitalsourcecomment, $this->getContractpartnerName( $moneyflow ['mcp_contractpartnerid'] ), $moneyflow ['comment'], $moneyflowid, $owner );
							$db_array_id = count( $db_array );

							if ($diff_capitalsource === false) {
								$matching_ids [] = array (
										'file' => $file_array_id,
										'db' => $db_array_id
								);
							} else {
								$diff_source_ids [] = array (
										'file' => $file_array_id,
										'db' => $db_array_id
								);
							}
						} else {
							$only_in_file_ids [] = array (
									'file' => $file_array_id
							);
						}
					} else {
						$only_in_file_ids [] = array (
								'file' => $file_array_id
						);
					}
				}
			}

			$moneyflows = CallServer::getInstance()->getAllMoneyflowsByDateRangeCapitalsourceId( $startdate, $enddate, $all_data ['mcs_capitalsourceid'] );

			$all_not_mon_data_cnt = 0;
			if ($matching_moneyflowids) {
				foreach ( $moneyflows as $moneyflow ) {
					if (array_search( $moneyflow ['moneyflowid'], $matching_moneyflowids ) === FALSE) {

						if ($moneyflow ['mcs_capitalsourceid'] == $all_data ['mcs_capitalsourceid']) {
							$my_capitalsourcecomment = $capitalsourcecomment;
						} else {
							$my_capitalsourcecomment = $this->getCapitalsourceComment( $moneyflow ['mcs_capitalsourceid'] );
						}

						if ($moneyflow ['mur_userid'] == USERID) {
							$owner = true;
						} else {
							$owner = false;
						}

						$db_array = $this->fill_db_array( $db_array, $moneyflow ['bookingdate'], $moneyflow ['invoicedate'], $moneyflow ['amount'], $my_capitalsourcecomment, $this->getContractpartnerName( $moneyflow ['mcp_contractpartnerid'] ), $moneyflow ['comment'], $moneyflow ['moneyflowid'], $owner );
						$db_array_id = count( $db_array );

						$only_in_db_ids [] = array (
								'db' => $db_array_id
						);
					}
				}
			}

			$this->template->assign( 'DB_ARRAY', $db_array );
			$this->template->assign( 'FILE_ARRAY', $file_array );

			$this->template->assign( 'ONLY_IN_FILE_IDS', $only_in_file_ids );
			$this->template->assign( 'ONLY_IN_DB_IDS', $only_in_db_ids );
			$this->template->assign( 'DIFF_SOURCE_IDS', $diff_source_ids );
			$this->template->assign( 'MATCHING_IDS', $matching_ids );

			$this->parse_header();
			return $this->fetch_template( 'display_analyze_cmp_data.tpl' );
		}
	}
}
?>
