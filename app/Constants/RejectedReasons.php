<?php

namespace App\Constants;

class RejectedReasons
{
    const DOES_NOT_MATCH_NAME             = 0b000000000000000000000000000001; // 1
    const DOES_NOT_FOUND_LOCATION         = 0b000000000000000000000000000010; // 2
    const DOES_NOT_MATCH_RELATIVE         = 0b000000000000000000000000000100; // 4
    const DOES_NOT_MATCH_DATAPOINT        = 0b000000000000000000000000001000; // 8
    const DOES_NOT_MATCH_AGE              = 0b000000000000000000000000010000; // 16
    const DOES_NOT_MATCH_PHONE            = 0b000000000000000000000000100000; // 32
    const DOES_NOT_MATCH_EMAIL            = 0b000000000000000000000001000000; // 64
    const DOES_NOT_MATCH_VERIFIEDUSERNAME = 0b000000000000000000000010000000; // 128
    const DOES_NOT_MATCH_MIDDELNAME       = 0b000000000000000000000100000000; // 256
    const DOES_NOT_MATCH_SMALLCITY        = 0b000000000000000000001000000000; // 512
    const DOES_NOT_MATCH_BIGCITY          = 0b000000000000000000010000000000; // 1024
    const DOES_NOT_FOUND_USERNAME         = 0b000000000000000000100000000000; // 2048
    const DOES_NOT_FOUND_NAME             = 0b000000000000000001000000000000; // 4096
    const DOES_NOT_MATCH_UNIQUENAME       = 0b000000000000000010000000000000; // 8192
    const DOES_NOT_MATCH_INPUTPHONE       = 0b000000000000000100000000000000; // 16384
    const DOES_NOT_MATCH_INPUTEMAIL       = 0b000000000000001000000000000000; // 32768
    const BANNED_DOMAIN                   = 0b000000000000010000000000000000; // 65536
    const WIKIPEDIA_NOT_EN                = 0b000000000000100000000000000000; // 131072
    const USER_BANNED_DOMAIN              = 0b000000000001000000000000000000; // 262144
    const DOES_NOT_MATCH_PARTIALCITY      = 0b000000000010000000000000000000; // 524288
    const DOES_NOT_MATCH_STATE            = 0b000000000100000000000000000000; // 1048576
    const DOES_NOT_MATCH_INPUTUSERNAME    = 0b000000001000000000000000000000; // 2097152
    const MATCHNAME_RELATIVE_NOT_ONLY     = 0b000000010000000000000000000000; // 4194304
    const IS_UNIQUENAME                   = 0b000000100000000000000000000000; // 8388608
    const DOES_NOT_MATCH_USERNAME         = 0b000001000000000000000000000000; // 16777216
    const DOES_NOT_MATCH_LOCATION         = 0b000010000000000000000000000000; // 33554432
    const EMPTY_URL                       = 0b000100000000000000000000000000; // 67108864
    const DOES_NOT_MATCH_INPUTNAME        = 0b001000000000000000000000000000; // 134217728
    const IS_ONLY_ONE                     = 0b010000000000000000000000000000; // 268435456
    const DOES_NOT_MATCH_PEOPLEUENAME     = 0b100000000000000000000000000000; // 536870912
}
