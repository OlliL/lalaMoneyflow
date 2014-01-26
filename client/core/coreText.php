<?php
#-
# Copyright (c) 2006-2014 Oliver Lehmann <oliver@FreeBSD.org>
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
# $Id: coreText.php,v 1.16 2014/01/26 12:24:47 olivleh1 Exp $
#

require_once 'core/core.php';

class coreText extends core {

	function coreText() {
		parent::__construct();
	}

	function get_text( $id, $type ) {
		return htmlentities( \rest\base\config\CacheManager::getInstance()->get('lalaMoneyflowText#'.GUI_LANGUAGE.'-'.$id), ENT_COMPAT | ENT_HTML401, ENCODING );
	}
	function get_lang_data( $id ) {
		return $this->select_rows( "	SELECT textid
						      ,text
						  FROM text
						 WHERE mla_languageid = $id" );
	}

	function update_text( $id, $languageid, $text ) {
		return $this->update_row( "	UPDATE text
						   SET text = '$text'
						 WHERE textid         = $id
						   AND mla_languageid = $languageid" );
	}

	function get_error( $id ) {
		return $this->get_text( $id, 'e' );
	}

	function get_graph( $id ) {
		return $this->get_text( $id, 'g' );
	}
}
