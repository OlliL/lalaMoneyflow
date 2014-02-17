<?php
//
// Copyright (c) 2006-2014 Oliver Lehmann <oliver@FreeBSD.org>
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
// $Id: coreLanguages.php,v 1.16 2014/02/17 19:07:27 olivleh1 Exp $
//
require_once 'core/core.php';

class coreLanguages extends core {
	private $languageConf = 'rest/client/locale/languages.conf';
	private $columnNames = array (
			'languageid',
			'language'
	);

	function coreLanguages() {
		parent::__construct();
	}

	private final function getLanguagesFile() {
		return file( $this->languageConf );
	}

	private final function splitLanguagesFile() {
		return array_map( 'str_getcsv', $this->getLanguagesFile() );
	}

	private final function mapToResult($csvRows) {
		foreach ( $csvRows as $csvRow ) {
			$result [] = array_combine( $this->columnNames, $csvRow );
			$sort [] = strtolower( $csvRow [1] );
		}
		if (count( $sort ) > 1)
			array_multisort( $sort, $result );
		return $result;
	}

	public final function count_all_data() {
		return count( $this->getLanguagesFile() );
	}

	public final function get_all_data() {
		return $this->mapToResult( $this->splitLanguagesFile() );
	}

	public final function get_all_matched_data($letter) {
		foreach ( $this->splitLanguagesFile() as $csvRow ) {
			if (strcasecmp( (substr( $csvRow [1], 0, 1 )), $letter ) === 0)
				$csvRows [] = $csvRow;
		}
		return $this->mapToResult( $csvRows );
	}

	public final function get_all_index_letters() {
		foreach ( $this->splitLanguagesFile() as $csvRow ) {
			$result [] = strtoupper( substr( $csvRow [1], 0, 1 ) );
		}
		$result = array_unique( $result );
		sort( $result );
		return $result;
	}

	public final function add_language($language) {
		$cvsRows = $this->splitLanguagesFile();
		$nextId = 0;

		$fp = fopen( $this->languageConf, 'w' );
		if ($fp !== FALSE) {
			foreach ( $cvsRows as $csvRow ) {
				fputcsv( $fp, $csvRow );
				if ($nextId < $csvRow [0])
					$nextId = $csvRow [0];
			}

			fputcsv( $fp, array (
					++ $nextId,
					$language
			) );
			fclose( $fp );
		}
		return $nextId;
	}

	public final function get_language_name($id) {
		foreach ( $this->splitLanguagesFile() as $csvRow ) {
			if ($csvRow [0] == $id)
				$name = $csvRow [1];
		}
		return $name;
	}
}
