<?php
//
// Copyright (c) 2013-2014 Oliver Lehmann <oliver@laladev.org>
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
// $Id: SessionControllerHandler.php,v 1.9 2014/02/28 22:19:47 olivleh1 Exp $
//
namespace client\handler;

use client\mapper\ClientArrayMapperEnum;
use base\JsonAutoMapper;

class SessionControllerHandler extends AbstractHandler {
	private static $instance;

	protected function __construct() {
		parent::__construct();
		parent::addMapper( 'client\mapper\ArrayToValidationItemTransportMapper', ClientArrayMapperEnum::VALIDATIONITEM_TRANSPORT );
	}

	public static function getInstance() {
		if (! isset( self::$instance )) {
			self::$instance = new SessionControllerHandler();
		}
		return self::$instance;
	}

	protected final function getCategory() {
		return 'session';
	}

	public final function doLogon($user, $password) {
		$response = parent::getJson( 'logon', array (
				$user,
				$password
		) );
		if (is_array( $response )) {
			$doLogonResponse = JsonAutoMapper::mapAToB( $response, '\\api\\model\\session' );
			$result = array (
					'mur_userid' => $doLogonResponse->getUserid(),
					'dateformat' => $doLogonResponse->getSettingDateFormat(),
					'displayed_language' => $doLogonResponse->getSettingDisplayedLanguage(),
					'att_new' => $doLogonResponse->getAttributeNew(),
					'perm_admin' => $doLogonResponse->getPermissionAdmin()
			);
		}
		return $result;
	}
}

?>