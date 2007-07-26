<?php
#-
# Copyright (c) 2005-2006 Oliver Lehmann <oliver@FreeBSD.org>
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
# $Id: moduleSettings.php,v 1.6 2007/07/26 15:36:54 olivleh1 Exp $
#

require_once 'module/module.php';
require_once 'core/coreCurrencies.php';
require_once 'core/coreLanguages.php';
require_once 'core/coreSettings.php';
require_once 'core/coreUsers.php';

class moduleSettings extends module {

	function moduleSettings() {
		$this->module();
		$this->coreCurrencies=new coreCurrencies();
		$this->coreLanguages=new coreLanguages();
		$this->coreSettings=new coreSettings();
		$this->coreUsers=new coreUsers();
	}

	function general_settings( $data_is_valid, $userid, $realaction, $language, $currency ) {

		switch( $realaction ) {
			case 'save':
				if( $data_is_valid ) {
					$this->coreSettings->set_displayed_currency( $userid, $currency );
					$this->coreSettings->set_displayed_language( $userid, $language );
				}
				break;
			default:
				break;
		}

		if( $data_is_valid ) {
			$this->template->assign( 'CURRENCY',        $this->coreSettings->get_displayed_currency( $userid ) );
			$this->template->assign( 'LANGUAGE',        $this->coreSettings->get_displayed_language( $userid ) );
		} else {
			$this->template->assign( 'CURRENCY',        $currency );
			$this->template->assign( 'LANGUAGE',        $language );
		}

		$this->template->assign( 'CURRENCY_VALUES', $this->coreCurrencies->get_all_data() );
		$this->template->assign( 'LANGUAGE_VALUES', $this->coreLanguages->get_all_data() );
		$this->template->assign( 'ERRORS',          $this->get_errors() );

		$this->parse_header();
	}

	function display_personal_settings( $realaction, $language, $currency, $password1, $password2 ) {

		$data_is_valid=true;
		
		switch( $realaction ) {
			case 'save':
				if( $this->coreUsers->check_new_attribute( USERID ) == 1 && ( empty( $password1 ) && empty( $password2 ) ) ) {
					add_error( 152 );
					$data_is_valid = false;
				} elseif( $password1 != $password2 ) {
					add_error( 137 );
					$data_is_valid = false;
				} elseif( !empty( $password1 ) ) {
					$this->coreUsers->set_password( USERID, $password1 );
				}
				break;
			default:
				break;
		}

		$this->general_settings( $data_is_valid, USERID, $realaction, $language, $currency );

		
		return $this->fetch_template( 'display_personal_settings.tpl' );
	}

	function display_system_settings( $realaction, $language, $currency ) {

		$this->general_settings( true, 0, $realaction, $language, $currency );

		return $this->fetch_template( 'display_system_settings.tpl' );
	}

}
?>
