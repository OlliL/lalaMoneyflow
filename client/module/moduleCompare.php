<?php
#-
# Copyright (c) 2006-2007 Oliver Lehmann <oliver@FreeBSD.org>
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
# $Id: moduleCompare.php,v 1.2 2007/10/26 09:37:08 olivleh1 Exp $
#

require_once 'module/module.php';
require_once 'core/coreCompare.php';
require_once 'core/coreCapitalSources.php';
require_once 'core/coreContractPartners.php';
require_once 'core/coreMoneyFlows.php';
require_once 'core/coreSettings.php';

class moduleCompare extends module {

	function moduleCompare() {
		$this->module();
		$this->coreCompare          = new coreCompare();
		$this->coreCapitalSources   = new coreCapitalSources();
		$this->coreContractPartners = new coreContractPartners();
		$this->coreMoneyFlows       = new coreMoneyFlows();
		$this->coreSettings         = new coreSettings();

		$date_format = $this->coreSettings->get_date_format( USERID );
		$this->date_format = $date_format['dateformat'];
	}

	function display_upload_form( $all_data = array() ) {

		$format_values        = $this->coreCompare->get_all_data();
		$capitalsource_values = $this->coreCapitalSources->get_valid_comments();

		if( count( $all_data ) === 0 ) {
			$all_data['startdate'] = convert_date_to_gui(date("Y-m-d", mktime(0, 0, 0, date( 'm', time() )  , 1, date( 'Y', time() ) ) ), $this->date_format );
			$all_data['enddate']   = convert_date_to_gui(date("Y-m-d", mktime(0, 0, 0, date( 'm', time() )+1, 0, date( 'Y', time() ) ) ), $this->date_format );
		}

		$this->template->assign( 'CAPITALSOURCE_VALUES', $capitalsource_values );
		$this->template->assign( 'FORMAT_VALUES',        $format_values        );
		$this->template->assign( 'ALL_DATA',             $all_data             );
		$this->template->assign( 'ERRORS',               $this->get_errors()   );

		$this->parse_header();
		return $this->fetch_template( 'display_upfrm_cmp_data.tpl' );
	}

	function display_analyze_form( $file, $all_data ) {
	
		$startdate  = $all_data['startdate'];
		$enddate    = $all_data['enddate'];
		$valid_data = true;
		
		if( !empty( $startdate ) ) {
			$startdate             = convert_date_to_db( $startdate, $this->date_format );
			if( $startdate === false ) {
				add_error( 147, array( $this->date_format ) );
				$all_data['startdate_error'] = 1;
				$valid_data = false;
			}
		}

		if( !empty( $enddate ) ) {
			$enddate             = convert_date_to_db( $enddate, $this->date_format );
			if( $enddate === false ) {
				add_error( 147, array( $this->date_format ) );
				$all_data['enddate_error'] = 1;
				$valid_data = false;
			}
		}

		if( !$file['tmp_name'] ) {
			add_error( 191 );
			$valid_data = false;
		}

		if( $valid_data === false ) {
			return $this->display_upload_form( $all_data );
		} else {
		
			# change given date to timespam for later "between" comparsion
			$startdate = convert_date_to_timestamp( $all_data['startdate'], $this->date_format );
			$enddate   = convert_date_to_timestamp( $all_data['enddate'], $this->date_format );
			
			$format_data = $this->coreCompare->get_id_data( $all_data['format'] );

			$capitalsourcecomment = $this->coreCapitalSources->get_comment( $all_data['mcs_capitalsourceid'] );

			$lines = file( $file['tmp_name'] );
			$match = 0;
			$all_cmp_data_cnt     = 0;
			$all_not_cmp_data_cnt = 0;
			foreach ($lines as $line_num => $line) {
				if( preg_match( $format_data['startline'], $line ) ) {
					$match = 1;
					continue;
				}
				if( $match === 1 ) {
					$cmp_data   = split( $format_data['delimiter'], $line );
					foreach( $cmp_data as $ind => $data ) {
						$cmp_data[$ind] = preg_replace('/^"(.*)"$/','$1',$data);
					}
					$date       = $cmp_data[$format_data['pos_date'] -1];
					$date_stamp = convert_date_to_timestamp( $date, $format_data['fmt_date'] );
					$date_db    = convert_date_to_db( $date, $format_data['fmt_date'] );
					$hitlist    = array();
					$mon_data   = array();

					if( $date_stamp >= $startdate &&
					    $date_stamp <= $enddate ) {
					    	$amount = str_replace( $format_data['fmt_amount_thousand'], '', $cmp_data[$format_data['pos_amount'] -1 ] );
						$amount = str_replace( $format_data['fmt_amount_decimal'], '.', $amount );
						
						$partner = '';
						if( $format_data['pos_partner'] ) {
							$partner = $cmp_data[$format_data['pos_partner'] - 1];
						}

						if( $format_data['pos_partner_alt'] ) {
							if( $cmp_data[$format_data['pos_partner_alt_pos_key'] - 1 ] == $format_data['pos_partner_alt_keyword'] ) {
								$partner = $cmp_data[$format_data['pos_partner_alt'] - 1];
							}
						}
						
						$results = $this->coreMoneyFlows->find_single_moneyflow( $date_db, 5, $amount );
						if( is_array( $results ) ) {
							$result_count = count( $results );
							
							foreach( $results as $moneyflowid ) {
								$moneyflow = $this->coreMoneyFlows->get_id_data( $moneyflowid );
								
								$mon_data[$moneyflowid] = $moneyflow;
								
								$hitlist[$moneyflowid] = 0;
								
								if( $result_count > 1 ) {
									# more than one result the program has now trying to find the right one
									# this is done by counting different aspects of a flow to generate a hitlist
									# the result with the highest hitcount is treated as the matching one.
									
									if( $moneyflow['bookingdate'] == $date_db )
										$hitlist[$moneyflowid] += 10;
										
									if( $moneyflow['invoiceate'] == $date_db )
										$hitlist[$moneyflowid] += 5;

									if( $moneyflow['mcs_capitalsourceid'] == $all_data['mcs_capitalsourceid'] )
										$hitlist[$moneyflowid] += 5;
										
									# does our input-file contain contractpartner information?
									if( !empty( $partner ) ) {
										$cmp_partner = $partner;
										$mon_partner = $this->coreContractPartners->get_name( $moneyflow['mcp_contractpartnerid'] );
										
										$split_pattern = '[\., -]';
										
										$matching_words = 0;
										$words          = 0;
										foreach( split( $split_pattern, $cmp_partner ) as $cmp_word ) {
											$words++;
											foreach( split( $split_pattern, $mon_partner) as $mon_word ) {
												if( strcasecmp( $mon_word, $cmp_word ) === 0 ) {
													$hitlist[$moneyflowid] += 10;
													$matching_words++;
												} elseif( soundex( $mon_word ) == soundex( $cmp_word ) ) {
													$hitlist[$moneyflowid] += 8;
													$matching_words++;
												}
											}
										}
										
										if( $matching_words == $words && $matching_words != 0 )
											$hitlist[$moneyflowid] += 5;
									}
								} else {
									$hitlist[$moneyflowid] = 100;
								}
							}
							arsort($hitlist);
							$moneyflowid = key($hitlist);
							$moneyflow = $mon_data[$moneyflowid];
							
							$matching_moneyflowids[] = $moneyflowid;
							$all_cmp_data[$all_cmp_data_cnt]['cmp']['bookingdate']                  = convert_date_to_gui( $date_db, $this->date_format );
							$all_cmp_data[$all_cmp_data_cnt]['cmp']['amount']                       = $amount;
							$all_cmp_data[$all_cmp_data_cnt]['cmp']['capitalsourcecomment']         = $capitalsourcecomment;
							if( !empty( $partner ) ) {
								$all_cmp_data[$all_cmp_data_cnt]['cmp']['contractpartnername']  = $partner;
							}
							if( !empty( $format_data['pos_comment'] ) ) {
								$all_cmp_data[$all_cmp_data_cnt]['cmp']['comment']              = $cmp_data[$format_data['pos_comment'] - 1];
							}
							
							$all_cmp_data[$all_cmp_data_cnt]['mon']['bookingdate']                  = convert_date_to_gui( $moneyflow['bookingdate'], $this->date_format );
							$all_cmp_data[$all_cmp_data_cnt]['mon']['invoicedate']                  = convert_date_to_gui( $moneyflow['invoicedate'], $this->date_format );
							$all_cmp_data[$all_cmp_data_cnt]['mon']['amount']                       = $moneyflow['amount'];
							if( $moneyflow['mcs_capitalsourceid'] == $all_data['mcs_capitalsourceid'] ) {
								$all_cmp_data[$all_cmp_data_cnt]['mon']['capitalsourcecomment'] = $capitalsourcecomment;
							} else {
								$all_cmp_data[$all_cmp_data_cnt]['mon']['capitalsourcecomment'] = $this->coreCapitalSources->get_comment( $moneyflow['mcs_capitalsourceid'] );
							}
							$all_cmp_data[$all_cmp_data_cnt]['mon']['contractpartnername']          = $this->coreContractPartners->get_name( $moneyflow['mcp_contractpartnerid'] );
							$all_cmp_data[$all_cmp_data_cnt]['mon']['comment']                      = $moneyflow['comment'];

							$all_cmp_data_cnt++;
						} else {
							$all_not_cmp_data[$all_not_cmp_data_cnt]['bookingdate']                  = convert_date_to_gui( $date_db, $this->date_format );
							$all_not_cmp_data[$all_not_cmp_data_cnt]['amount']                       = $amount;
							$all_not_cmp_data[$all_not_cmp_data_cnt]['capitalsourcecomment']         = $capitalsourcecomment;
							if( !empty( $partner ) ) {
								$all_not_cmp_data[$all_not_cmp_data_cnt]['contractpartnername']  = $partner;
							}
							if( !empty( $format_data['pos_comment'] ) ) {
								$all_not_cmp_data[$all_not_cmp_data_cnt]['comment']              = $cmp_data[$format_data['pos_comment'] - 1];
							}

							$all_not_cmp_data_cnt++;
						}
					}
				}
				
			}

			$moneyflows = $this->coreMoneyFlows->get_all_date_source_data( $all_data['mcs_capitalsourceid'], $all_data['startdate'], $all_data['enddate'] );

			$all_not_mon_data_cnt = 0;
			foreach( $moneyflows as $moneyflow ) {
				if( array_search( $moneyflow['moneyflowid'], $matching_moneyflowids ) === FALSE ) {
				
					$all_not_mon_data[$all_not_mon_data_cnt]['bookingdate'] 		 = convert_date_to_gui( $moneyflow['bookingdate'], $this->date_format );
					$all_not_mon_data[$all_not_mon_data_cnt]['invoicedate'] 		 = convert_date_to_gui( $moneyflow['invoicedate'], $this->date_format );
					$all_not_mon_data[$all_not_mon_data_cnt]['amount']			 = $moneyflow['amount'];
					if( $moneyflow['mcs_capitalsourceid'] == $all_data['mcs_capitalsourceid'] ) {
						$all_not_mon_data[$all_not_mon_data_cnt]['capitalsourcecomment'] = $capitalsourcecomment;
					} else {
						$all_not_mon_data[$all_not_mon_data_cnt]['capitalsourcecomment'] = $this->coreCapitalSources->get_comment( $moneyflow['mcs_capitalsourceid'] );
					}
					$all_not_mon_data[$all_not_mon_data_cnt]['contractpartnername'] 	 = $this->coreContractPartners->get_name( $moneyflow['mcp_contractpartnerid'] );
					$all_not_mon_data[$all_not_mon_data_cnt]['comment']			 = $moneyflow['comment'];
					$all_not_mon_data_cnt++;
				}
			}
			$this->template->assign( 'ALL_CMP_DATA',     $all_cmp_data     );
			$this->template->assign( 'ALL_NOT_CMP_DATA', $all_not_cmp_data );
			$this->template->assign( 'ALL_NOT_MON_DATA', $all_not_mon_data );
			$this->parse_header();
			return $this->fetch_template( 'display_analyze_cmp_data.tpl' );
		}
	}
}
?>
