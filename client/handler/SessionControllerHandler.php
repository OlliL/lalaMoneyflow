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
// $Id: SessionControllerHandler.php,v 1.3 2014/02/16 14:43:13 olivleh1 Exp $
//
namespace rest\client\handler;

use rest\client\util\CallServerUtil;
use rest\base\AbstractJsonSender;
use rest\client\mapper\ClientArrayMapperEnum;
use rest\base\JsonAutoMapper;

class SessionControllerHandler extends AbstractJsonSender {
	private static $instance;
	private static $callServer;

	protected function __construct() {
		parent::addMapper( 'rest\client\mapper\ArrayToValidationItemTransportMapper', ClientArrayMapperEnum::VALIDATIONITEM_TRANSPORT );
	}

	public static function getInstance() {
		if (! isset( self::$instance )) {
			self::$instance = new SessionControllerHandler();
			self::$callServer = CallServerUtil::getInstance();
		}
		return self::$instance;
	}

	public final function doLogon($user, $password) {
		$url = URLPREFIX . SERVERPREFIX . 'session/logon/' . $user . '/' . $password;
		$response = self::$callServer->getJson( $url );
		if (is_array( $response )) {
			$doLogonResponse = JsonAutoMapper::mapAToB( $response, '\\rest\\api\\model\\session' );
			$result = array (
					'mur_userid' => $doLogonResponse->getUserid(),
					'username' => $doLogonResponse->getUserName(),
					'sessionid' => $doLogonResponse->getSessionId(),
					'dateformat' => $doLogonResponse->getSettingDateFormat(),
					'displayed_language' => $doLogonResponse->getSettingDisplayedLanguage()
			);
		}
		return $result;
	}
}

?>