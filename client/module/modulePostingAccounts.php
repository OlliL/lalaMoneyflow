<?php
//
// Copyright (c) 2006-2014 Oliver Lehmann <oliver@laladev.org>
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
// $Id: modulePostingAccounts.php,v 1.4 2014/03/07 20:41:36 olivleh1 Exp $
//
namespace client\module;

use client\handler\PostingAccountControllerHandler;
use base\ErrorCode;

if (ENABLE_JPGRAPH) {
	require_once 'jpgraph.php';
	require_once 'jpgraph_bar.php';
}

class modulePostingAccounts extends module {

	public final function __construct() {
		parent::__construct();
	}

	public final function display_list_postingAccounts($letter) {
		$listPostingAccounts = PostingAccountControllerHandler::getInstance()->showPostingAccountList( $letter );
		$all_index_letters = $listPostingAccounts ['initials'];
		$all_data = $listPostingAccounts ['postingAccounts'];

		$this->template->assign( 'ALL_DATA', $all_data );
		$this->template->assign( 'COUNT_ALL_DATA', count( $all_data ) );
		$this->template->assign( 'ALL_INDEX_LETTERS', $all_index_letters );

		$this->parse_header();
		return $this->fetch_template( 'display_list_postingaccounts.tpl' );
	}

	public final function display_edit_postingAccount($realaction, $postingaccountid, $all_data) {
		$close = 0;
		switch ($realaction) {
			case 'save' :
				$all_data ['postingaccountid'] = $postingaccountid;
				if ($postingaccountid == 0)
					$ret = PostingAccountControllerHandler::getInstance()->createPostingAccount( $all_data );
				else
					$ret = PostingAccountControllerHandler::getInstance()->updatePostingAccount( $all_data );

				if ($ret === true) {
					$close = 1;
				} else {
					foreach ( $ret ['errors'] as $validationResult ) {
						$error = $validationResult ['error'];

						$this->add_error( $error );

						switch ($error) {
							case ErrorCode::NAME_MUST_NOT_BE_EMPTY :
							case ErrorCode::POSTINGACCOUNT_WITH_SAME_NAME_ALREADY_EXISTS :
								$all_data ['name_error'] = 1;
								break;
						}
					}
				}
			default :
				if (! is_array( $all_data )) {
					if ($postingaccountid > 0) {
						$all_data = PostingAccountControllerHandler::getInstance()->showEditPostingAccount( $postingaccountid );
					} else {
						$all_data ['name'] = '';
					}
				}
				break;
		}

		$this->template->assign( 'CLOSE', $close );
		$this->template->assign( 'POSTINGACCOUNTID', $postingaccountid );
		$this->template->assign( 'ALL_DATA', $all_data );
		$this->template->assign( 'ERRORS', $this->get_errors() );

		$this->parse_header( 1 );
		return $this->fetch_template( 'display_edit_postingaccount.tpl' );
	}

	public final function display_delete_postingAccount($realaction, $postingaccountid, $force) {
		switch ($realaction) {
			case 'yes' :
				if (PostingAccountControllerHandler::getInstance()->deletePostingAccountById( $postingaccountid )) {
					$this->template->assign( 'CLOSE', 1 );
					break;
				}
			default :
				if ($postingaccountid > 0) {
					$all_data = PostingAccountControllerHandler::getInstance()->showDeletePostingAccount( $postingaccountid );
					if ($all_data) {
						$this->template->assign( 'ALL_DATA', $all_data );
					}
				}
				break;
		}

		$this->template->assign( 'ERRORS', $this->get_errors() );

		$this->parse_header( 1 );
		return $this->fetch_template( 'display_delete_postingaccount.tpl' );
	}

	private function randomColor() {
		$possibilities = array (
				1,
				2,
				3,
				4,
				5,
				6,
				7,
				8,
				9,
				"A",
				"B",
				"C",
				"D"
		);
		shuffle( $possibilities );
		$color = "#";
		for($i = 1; $i <= 6; $i ++) {
			$color .= $possibilities [rand( 0, 14 )];
		}
		return $color;
	}

	public final function plot_postingAccounts($yearFrom, $yearTil) {
		$plotPostingAccounts = PostingAccountControllerHandler::getInstance()->plotPostingAccounts( $yearFrom, $yearTil );
		$postingAccounts = $plotPostingAccounts ['postingAccounts'];
		$all_data = $plotPostingAccounts ['data'];
		foreach ( $postingAccounts as $key => $postingAccount ) {
			$postingAccountKeys [$postingAccount ['postingaccountid']] = $key;
			$postingAccountNames [] = utf8_decode( $postingAccount ['name'] );
		}
		foreach ( $all_data as $data ) {
			if ($data ['postingaccountid'] == 18)
				continue;
			$year = date( 'Y', $data ['date_ts'] );
			$account_key = $postingAccountKeys [$data ['postingaccountid']];
			$plot_data [$year] [$account_key] += $data ['amount'];
		}

		foreach ( $plot_data as $data ) {
			$plot = new \BarPlot( $data );
			$plot->SetColor( "white" );
			$plot->SetFillColor( $this->randomColor() );
			$plots [] = $plot;
		}
		// Create the graph. These two calls are always required
		$graph = new \Graph( 900, 900 );
		$graph->SetScale( "textlin" );
		$graph->Set90AndMargin( 50, 40, 40, 40 );

		$graph->ygrid->SetFill( false );

		$graph->xaxis->SetTickLabels( $postingAccountNames );

		$graph->yaxis->HideLine( false );
		$graph->yaxis->HideTicks( false, false );


		$gbplot = new \GroupBarPlot( $plots );
		// ...and add it to the graPH
		$graph->Add( $gbplot );
		$graph->title->Set( "Bar Plots" );

		// Display the graph
		$graph->Stroke();
	}
}
?>
