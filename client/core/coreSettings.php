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
# $Id: coreSettings.php,v 1.3 2006/12/21 10:35:01 olivleh1 Exp $
#

require_once 'core/core.php';

class coreSettings extends core {

	function coreSettings() {
		$this->core();
	}

	function get_value( $name ) {
		return $this->select_col( "SELECT value FROM settings WHERE name='$name' AND userid=".USERID." LIMIT 1" );
	}

	function set_value( $name, $value ) {
		return $this->insert_row( "INSERT INTO settings (userid,name,value) VALUES (".USERID.",'$name','$value') ON DUPLICATE KEY UPDATE value=VALUES(value)" );
	}

	function get_displayed_currency() {
		return $this->get_value( 'displayed_currency' );
	}

	function get_displayed_language() {
		return $this->get_value( 'displayed_language' );
	}

	function set_displayed_currency( $currency ) {
		return $this->set_value( 'displayed_currency', $currency );
	}

	function set_displayed_language( $language ) {
		return $this->set_value( 'displayed_language', $language );
	}
}
