<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Routing\Controller as BaseController;
use App\Http\Traits\ApiResponder;

/**
 * Базовый контроллер для API.
 *
 * Предоставляет унифицированные методы для возврата JSON-ответов.
 */
class ApiController extends BaseController
{
    use ApiResponder;
}
