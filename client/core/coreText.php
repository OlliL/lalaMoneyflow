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
// $Id: coreText.php,v 1.17 2014/02/05 21:17:08 olivleh1 Exp $
//
require_once 'core/core.php';

class coreText extends core {
	private $inifile;

	function coreText() {
		parent::__construct();
		$this->inifile = null;
	}

	function get_text($id) {
		if ($this->inifile === null)
			$this->inifile = parse_ini_file( 'rest/client/locale/' . GUI_LANGUAGE . '.conf' );

		return htmlentities( $this->inifile ['TEXT_' . $id], ENT_COMPAT | ENT_HTML401, ENCODING );
	}

	function get_lang_data($id) {
		return $this->select_rows( "	SELECT textid
						      ,text
						  FROM text
						 WHERE mla_languageid = $id" );
	}

	function update_text($id, $languageid, $text) {
		return $this->update_row( "	UPDATE text
						   SET text = '$text'
						 WHERE textid         = $id
						   AND mla_languageid = $languageid" );
	}

	function get_error($id) {
		return $this->get_text( $id );
	}

	function get_graph($id) {
		return $this->get_text( $id );
	}
}
