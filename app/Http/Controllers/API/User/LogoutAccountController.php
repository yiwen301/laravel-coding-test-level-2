<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\User;

use App\Classes\Modules\ControllerLogic\User\LogoutAccountLogic;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * @author    Yi Wen, Tan <yiwentan301@gmail.com>
 */
class LogoutAccountController extends Controller {
    /**
     * @param LogoutAccountLogic $logic
     * @param Request            $request
     */
    public function execute(LogoutAccountLogic $logic, Request $request): void {
        $logic->execute($request);
    }
}
