<?php
//
// Copyright (c) 2005-2014 Oliver Lehmann <oliver@laladev.org>
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
// $Id: module.php,v 1.76 2014/03/01 19:32:34 olivleh1 Exp $
//
namespace client\module;

use client\core\coreText;
use client\util\Environment;

require_once 'Smarty.class.php';

abstract class module {
	protected $template;

	protected function __construct() {

		$this->template = new \Smarty();
		$this->template->registerPlugin( 'modifier', 'number_format', 'my_number_format' );
		$this->template->assign( 'ENV_INDEX_PHP', 'index.php' );
		$this->template->setCompileCheck( \Smarty::COMPILECHECK_OFF );

		if (! empty( $_SERVER ['HTTP_REFERER'] )) {
			$http_referer = $_SERVER ['HTTP_REFERER'];
		} else {
			$http_referer = '';
		}

		if (! empty( $_POST ['REFERER'] )) {
			$referer = $_POST ['REFERER'];
		} elseif (! empty( $_GET ['REFERER'] )) {
			$referer = $_GET ['REFERER'];
		} else {
			$referer = '';
		}

		if ((! empty( $_POST ['sr'] ) && $_POST ['sr'] == 1) || (! empty( $_GET ['sr'] ) && $_GET ['sr'] == 1)) {
			$this->template->assign( 'ENV_REFERER', htmlentities( $http_referer ) );
		} else {
			$this->template->assign( 'ENV_REFERER', htmlentities( $referer ) );
		}
	}

	protected final function get_errors() {
		global $ERRORS;
		$result = array ();
		if (is_array( $ERRORS )) {
			$coreText = new coreText();
			foreach ( $ERRORS as $error ) {
				$error_text = $coreText->get_text( $error ['id'] );
				if (array_key_exists( 'arguments', $error ) && is_array( $error ['arguments'] )) {
					foreach ( $error ['arguments'] as $id => $value ) {
						$error_text = str_replace( 'A' . ($id + 1) . 'A', $value, $error_text );
					}
				}
				$result [] = $error_text;
			}
		}
		return $result;
	}

	private final function loadLanguageFile() {
		$this->template->configLoad( 'locale/' . Environment::getInstance()->getSettingGuiLanguage() . '.conf' );
	}

	protected final function fetch_template($name, $cacheid = false) {
		if (! $cacheid) {
			$this->loadLanguageFile();
			$result = $this->template->fetch( './' . $name );
		} else {
			if (! $this->template->isCached( './' . $name, $cacheid ))
				$this->loadLanguageFile();
			$result = $this->template->fetch( './' . $name, $cacheid );
		}
		return $result;
	}

	protected final function parse_header($nonavi = 0) {
		$this->template->assign( 'REPORTS_YEAR', date( 'Y' ) );
		$this->template->assign( 'REPORTS_MONTH', date( 'm' ) );
		$this->template->assign( 'ENABLE_JPGRAPH', ENABLE_JPGRAPH );
		$this->template->assign( 'VERSION', '0.20.0' );
		$this->template->assign( 'NO_NAVIGATION', $nonavi );
		$admin = Environment::getInstance()->getUserPermAdmin();
		if ($admin) {
			$this->template->assign( 'IS_ADMIN', true );
		} else {
			$this->template->assign( 'IS_ADMIN', false );
		}
		$cache_id = USERID;
		$this->template->setCaching( true );
		$header = $this->fetch_template( 'display_header.tpl', 'header_' . $this->guiLanguage . '_' . $admin . '_' . $nonavi . '_' . $cache_id );
		$this->template->assign( 'HEADER', $header );

		$footer = $this->fetch_template( 'display_footer.tpl', 'footer_' . $this->guiLanguage . '_' . $cache_id );
		$this->template->assign( 'FOOTER', $footer );
		$this->template->setCaching( false );
	}
}
?>
