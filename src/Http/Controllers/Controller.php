<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;

/**
 * Base Controller class.
 */
class Controller extends \Illuminate\Routing\Controller
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;
}
