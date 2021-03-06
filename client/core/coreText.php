<?php
//
// Copyright (c) 2006-2015 Oliver Lehmann <lehmann@ans-netz.de>
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
// $Id: coreText.php,v 1.34 2016/02/06 22:10:02 olivleh1 Exp $
//
namespace client\core;

use client\util\Environment;
use base\Configuration;

class coreText extends core {
	private $inifile = null;
	private $encoding = null;

	public final function __construct() {
		parent::__construct();
		$this->encoding = Configuration::getInstance()->getProperty( 'encoding' );
	}

	private final function getFileName($id) {
		return 'locale/' . basename($id) . '.conf';
	}

	private final function getTextfile($id) {
		if ($this->inifile === null || ! array_key_exists( $id, $this->inifile ))
			$this->inifile [$id] = parse_ini_file( $this->getFileName( $id ) );
		return $this->inifile [$id];
	}

	public final function get_lang_data($id) {
		$inifile = $this->getTextfile( $id );
		return $inifile;
	}

	public final function update_text($id, $languageid, $text) {
		$lines = file( $this->getFileName( $languageid ) );
		foreach ( $lines as $key => $line ) {
			if (strpos( $line, $id . ' = ' ) === 0) {
				$lines [$key] = sprintf( "%s = '%s'\n", $id, htmlentities( $text, ENT_COMPAT | ENT_HTML401, $this->encoding ) );
			}
		}
		file_put_contents( $this->getFileName( $languageid ), $lines );
	}

	public final function create_new_textfile($sourceId, $newId) {
		copy( $this->getFileName( $sourceId ), $this->getFileName( $newId ) );
	}

	public final function get_text($id) {
		$inifile = $this->get_lang_data( Environment::getInstance()->getSettingGuiLanguage() );
		return $inifile ['TEXT_' . $id];
	}

	public final function get_graph($id) {
		return html_entity_decode( $this->get_text( $id ), ENT_COMPAT | ENT_HTML401, $this->encoding );
	}

	private final function getDomain($domain) {
		switch ($domain) {
			case 'MONTHS' :
				return array (
						1 => 155,
						2 => 156,
						3 => 157,
						4 => 158,
						5 => 159,
						6 => 160,
						7 => 161,
						8 => 162,
						9 => 163,
						10 => 164,
						11 => 165,
						12 => 166
				);
			case 'CAPITALSOURCE_TYPE' :
				return array (
						1 => 173,
						2 => 174,
						3 => 278,
						4 => 279,
						5 => 295
				);
			case 'CAPITALSOURCE_STATE' :
				return array (
						1 => 175,
						2 => 176
				);
		}
		return null;
	}

	public final function get_domain_data($domain) {
		$ids = self::getDomain( $domain );
		if (is_array( $ids )) {
			foreach ( $ids as $key => $id ) {
				$retval [] = array (
						'value' => $key,
						'text' => $this->get_text( $id )
				);
			}
		}
		return $retval;
	}

	public final function get_domain_meaning($domain, $value) {
		$ids = self::getDomain( $domain );
		return $this->get_text( $ids [$value] );
	}
}
