<?php

namespace App\Utils\Enum;

class EnumForStatus
{
    const OK = 200;
    const MESSAGE_200 = 'OK';

    const CREATED = 201;
    const MESSAGE_201 = 'Created';

    const NO_CONTENT = 204;
    const MESSAGE_204 = 'No content';

    const BAD_REQUEST = 400;
    const MESSAGE_400 = 'Bad request';

    const UNAUTHORIZED = 401;
    const MESSAGE_401 = 'Unauthorized';

    const FORBIDDEN = 403;
    const MESSAGE_403 = 'Forbidden';

    const NOT_FOUND = 404;
    const MESSAGE_404 = 'Not found';

    const METHOD_NOT_ALLOWED = 405;
    const MESSAGE_405 = 'Method not allowed';

    const INTERNAL_SERVER_ERROR = 500;
    const MESSAGE_500 = 'Internal server error';

    CONST BAD_GATEWAY = 502;
    const MESSAGE_502 = 'Bad gateway';

    const SERVICE_UNAVAILABLE = 503;
    const MESSAGE_503 = 'Service unavailable';
}