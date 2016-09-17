<?php
//
// Copyright (c) 2005-2016 Oliver Lehmann <oliver@laladev.org>
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
// $Id: module.php,v 1.89 2016/09/17 22:47:45 olivleh1 Exp $
//
namespace client\module;

use client\core\coreText;
use client\util\Environment;
use base\ErrorCode;
use client\util\ErrorHandler;
use client\util\DateUtil;

require_once 'Smarty.class.php';

abstract class module {
	protected $template;

	protected function __construct() {
		$this->template = new \Smarty();
		$this->template->error_unassigned = false;
		$this->template->error_reporting = E_ERROR;
		$this->template->registerPlugin( 'modifier', 'number_format', 'client\util\SmartyPlugin::my_number_format' );
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
		$result = array ();
		$errors = ErrorHandler::getErrors();
		if (is_array( $errors )) {
			$coreText = new coreText();
			foreach ( $errors as $error ) {
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
		$this->template->assign( 'VERSION', '0.22.0' );
		$this->template->assign( 'NO_NAVIGATION', $nonavi );
		$admin = Environment::getInstance()->getUserPermAdmin();
		if ($admin) {
			$this->template->assign( 'IS_ADMIN', true );
		} else {
			$this->template->assign( 'IS_ADMIN', false );
		}
		$cache_id = Environment::getInstance()->getUserId();
		$language = Environment::getInstance()->getSettingGuiLanguage();
		$this->template->setCaching( true );
		$header = $this->fetch_template( 'display_header.tpl', 'header_' . $language . '_' . $admin . '_' . $nonavi . '_' . $cache_id );
		$this->template->assign( 'HEADER', $header );

		$footer = $this->fetch_template( 'display_footer.tpl', 'footer_' . $language . '_' . $cache_id );
		$this->template->assign( 'FOOTER', $footer );
		$this->template->setCaching( false );
	}

	protected final function fix_amount(&$amount) {
		$return = true;

		if (preg_match( '/^-{0,1}[0-9]*([\.][0-9][0-9][0-9]){0,}([,][0-9]{1,2}){0,1}$/', $amount )) {
			$amount = str_replace( '.', '', $amount );
			$amount = str_replace( ',', '.', $amount );
		} elseif (preg_match( '/^-{0,1}[0-9]*([,][0-9][0-9][0-9]){0,}([\.][0-9]{1,2}){0,1}$/', $amount )) {
			$amount = str_replace( ',', '', $amount );
		} else {
			$this->add_error( ErrorCode::AMOUNT_IN_WRONG_FORMAT, array (
					$amount
			) );
			$return = false;
		}

		return $return;
	}

	protected final function add_error($id, $args = null) {
		ErrorHandler::addError( $id, $args );
	}

	protected final function dateIsValid($date) {
		return DateUtil::validateStringDate( $date );
	}

	protected final function convertDateToGui($date) {
		return DateUtil::convertStringDateToClient( $date );
	}

	protected final function sort_contractpartner($contractpartner_values) {
		$transliterator = \Transliterator::createFromRules( ':: NFD; :: [:Nonspacing Mark:] Remove; :: NFC;', \Transliterator::FORWARD );
		if (is_array( $contractpartner_values ) && count( $contractpartner_values ) > 0) {
			foreach ( $contractpartner_values as $key => $value ) {
				$sortKey1 [$key] = strtolower( $transliterator->transliterate( $value ['name'] ) );
			}

			array_multisort( $sortKey1, SORT_ASC, $contractpartner_values );
		}
		return $contractpartner_values;
	}
}
?>
