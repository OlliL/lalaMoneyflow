<?php
#-
# Copyright (c) 2006 Oliver Lehmann <oliver@FreeBSD.org>
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
# $Id: coreLanguages.php,v 1.5 2007/07/24 18:22:06 olivleh1 Exp $
#

require_once 'core/core.php';
require_once 'core/coreSettings.php';

class coreLanguages extends core {

	function coreLanguages() {
		$this->core();
		$this->coreSettings = new coreSettings();
	}

	function get_all_data() {
		return $this->select_rows( '	SELECT languageid
						      ,language
					          FROM languages
					         ORDER BY language' );
	}

	function get_displayed_language() {
		$id =$this->coreSettings->get_displayed_language( USERID );
		if( !empty( $id ) ) {
			$language=$this->get_language( $id );
			if( !empty( $language ) ) {
				return $language;
			} else {
				add_error( 17 );
			}
		} else {
			add_error( 18 );
		}
	}

	function get_language( $id ) {
		if( !empty( $id ) ) {
			return $this->select_col( "	SELECT language
						          FROM languages
						         WHERE languageid = $id
						         LIMIT 1" );
		} else {
			return;
		}
	}
}
