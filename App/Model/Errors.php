<?php
namespace App\Model;

class Errors
{
    // Empty JSON recieved
    const ERR_EMPTY_JSON = '100';

    // JSON decode error
    const ERR_JSON_ERROR = '101';

    //Invalid request, request has wrong format and can't be parsed
    const ERR_BAD_REQUEST = '200';

    // Invalid request, field '{$field}' is not set or not valid
    const ERR_REQUIRED_FIELD = '201';

    // Method disabled or not supported more
    const ERR_INVALID_METHOD = '202';

    // Bad arguments format
    const ERR_BAD_ARG_FORMAT = '203';

    // Wrong authkey
    const ERR_BAD_AUTHKEY = '204';

    // User have not access to this method
    const ERR_PERMISSION_DENY = '205';

    // Not enough require param: {$var}
    const ERR_NOT_ENOUGH_PARAM = '206';

    // Login already in use
    const ERR_LOGIN_USED = '207';

    // Invalid email
    const ERR_INVALID_EMAIL = '208';

    // Too large period
    const ERR_WRONG_PERIOD = '209';

    // Bad date format
    const ERR_BAD_DATE = '210';

    // Invalid domain
    const ERR_INVALID_DOMAIN = '211';

    // Error parsing protocol
    const ERR_INVALID_PROTOCOL = '212';

    //--- database
    // cant connect
    const ERR_DB_CONNECT = '301';

    // error while query
    const ERR_DB_QUERY = '302';

    // no resourse to fetch
    const ERR_DB_BAD_RESOURSE = '303';

}