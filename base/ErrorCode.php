<?php

//
// Copyright (c) 2013-2015 Oliver Lehmann <lehmann@ans-netz.de>
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
// $Id: ErrorCode.php,v 1.31 2016/12/31 22:19:12 olivleh1 Exp $
//
namespace base;

class ErrorCode {
	const UNKNOWN = 0;
	const CAPITALSOURCE_STILL_REFERENCED = 120;
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
	const USERNAME_PASSWORD_WRONG = 134;
	const PASSWORD_NOT_MATCHING = 137;
	const ACCOUNT_IS_LOCKED = 138;
	const USERNAME_IS_MANDATORY = 139;
	const PASSWORD_EMPTY = 140;
	const NO_SEARCH_CRITERIA_ENTERED = 141;
	const NO_GROUPING_CRITERIA_GIVEN = 142;
	const NO_DATA_FOUND = 143;
	const DATE_FORMAT_NOT_CORRECT = 147;
	const USER_HAS_DATA = 151;
	const PASSWORD_MUST_BE_CHANGED = 152;
	const MONTHLY_SETTLEMENT_ALREADY_EXISTS = 154;
	const INVALID_DATE_FORMAT_CHOOSEN = 180;
	const FILEUPLOAD_FAILED = 191;
	const WRONG_FILE_FORMAT = 199;
	const AMOUNT_IS_ZERO = 200;
	const NAME_ALREADY_EXISTS = 203;
	const ATTENTION = 204;
	const GROUP_IN_USE = 211;
	const NAME_MUST_NOT_BE_EMPTY = 218;
	const ACCOUNT_NUMBER_TO_LONG = 227;
	const BANK_CODE_TO_LONG = 228;
	const VALIDFROM_AFTER_VALIDTIL = 229;
	const LOGGED_OUT = 231;
	const POSTCODE_MUST_BE_A_NUMBER = 233;
	const POSTING_ACCOUNT_NOT_SPECIFIED = 234;
	const CAPITALSOURCE_DOES_NOT_EXIST = 19; // TODO
	const CONTRACTPARTNER_DOES_NOT_EXIST = 2; // TODO
	const CONTRACTPARTNER_NO_LONGER_VALID = 235;
	const MONEYFLOWS_OUTSIDE_VALIDITY_PERIOD = 236;
	const GROUP_WITH_SAME_NAME_ALREADY_EXISTS = 237;
	const VALIDFROM_NOT_DEFINED = 238;
	const VALIDTIL_NOT_DEFINED = 239;
	const USER_WITH_SAME_NAME_ALREADY_EXISTS = 240;
	const USER_IS_NO_ADMIN = 241;
	const GROUP_MUST_BE_SPECIFIED = 243;
	const VALIDFROM_EARLIER_THAN_TOMORROW = 244;
	const CLIENT_CLOCK_OFF = 245;
	const BOOKINGDATE_OUTSIDE_GROUP_ASSIGNMENT = 246;
	const POSTINGACCOUNT_STILL_REFERENCED = 249;
	const POSTINGACCOUNT_WITH_SAME_NAME_ALREADY_EXISTS = 250;
	const ACCOUNT_NUMBER_CONTAINS_ILLEGAL_CHARS_OR_IS_EMPTY = 274;
	const BANK_CODE_CONTAINS_ILLEGAL_CHARS_OR_IS_EMPTY = 275;
	const ACCOUNT_NUMBER_CONTAINS_ILLEGAL_CHARS = 276;
	const BANK_CODE_CONTAINS_ILLEGAL_CHARS = 277;
	const CAPITALSOURCE_IMPORT_NOT_ALLOWED = 283;
	const CAPITALSOURCE_NOT_FOUND = 284;
	const ACCOUNT_ALREADY_ASSIGNED_TO_OTHER_PARTNER = 286;
	const USERNAME_MUST_NOT_CONTAIN_SLASHES = 291;
	const PASSWORD_MUST_NOT_CONTAIN_SLASHES = 292;
	const SPLIT_ENTRIES_AMOUNT_IS_NOT_EQUALS_MONEYFLOW_AMOUNT = 299;
}

?>