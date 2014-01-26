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
// $Id: ErrorCode.php,v 1.6 2014/01/26 12:24:49 olivleh1 Exp $
//
namespace rest\base;

class ErrorCode extends \SplEnum {
	const __default = self::UNKNOWN;
	const UNKNOWN = 0;
	const CAPITALSOURCE_IN_USE_PERIOD = 121;
	const CAPITALSOURCE_USE_OUT_OF_VALIDITY = 122;
	const CONTRACTPARTNER_IN_USE = 124;
	const CAPITALSOURCE_IS_NOT_SET = 127;
	const CONTRACTPARTNER_IS_NOT_SET = 128;
	const INVOICEDATE_IN_WRONG_FORMAT = 129;
	const BOOKINGDATE_IN_WRONG_FORMAT = 130;
	const COMMENT_IS_NOT_SET = 131;
	const AMOUNT_IN_WRONG_FORMAT = 132;
	const NOTHING_MARKED_TO_ADD = 133;
	const DATE_FORMAT_NOT_CORRECT = 147;
	const AMOUNT_IS_ZERO = 200;
	const NAME_ALREADY_EXISTS = 203;
	const NAME_MUST_NOT_BE_EMPTY = 218;
	const ACCOUNT_NUMBER_NOT_A_NUMBER = 225;
	const BANK_CODE_NOT_A_NUMBER = 226;
	const ACCOUNT_NUMBER_TO_LONG = 227;
	const BANK_CODE_TO_LONG = 228;
	const VALIDFROM_AFTER_VALIDTIL = 229;
	const CAPITALSOURCE_DOES_NOT_EXIST = 19; // TODO
	const CONTRACTPARTNER_DOES_NOT_EXIST = 2; // TODO
}

?>