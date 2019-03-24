<?php

//
// Copyright (c) 2014-2015 Oliver Lehmann <lehmann@ans-netz.de>
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
// $Id: RESTAuthorization.php,v 1.6 2015/03/26 20:46:44 olivleh1 Exp $
//
namespace base;

class RESTAuthorization {
	public static $prefix = 'MNF';
	public static $header = 'Authentication';

	/**
	 * This function works basically as described in <a href="http://docs.aws.amazon.com/AmazonS3/latest/dev/RESTAuthentication.html">"Signing and Authenticating REST Requests"</a>
	 *
	 * @param unknown $url
	 * @param unknown $headers
	 * @param unknown $body
	 * @param unknown $ident
	 */
	public static final function getRESTAuthorization($secret, $httpVerb, $contentType, $url, $date, $body, $ident) {
		$signUrl = strstr( substr( $url, strpos( $url, '//' ) + 2 ), '/' );

		$stringToSign = $httpVerb;
		$stringToSign .= "\n";
		if ($body != null)
			$stringToSign .= md5( $body );
		$stringToSign .= "\n";
		if ($contentType != null)
			$stringToSign .= $contentType;
		$stringToSign .= "\n";
		$stringToSign .= $date;
		$stringToSign .= "\n";
		$stringToSign .= "\n";
		$stringToSign .= $signUrl;

		$authorization = self::$prefix . $ident . ':' . base64_encode( hash_hmac( 'sha1', utf8_encode( $stringToSign ), $secret ) );
		return $authorization;
	}
}

?>