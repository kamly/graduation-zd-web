<?php
/**
 * Created by PhpStorm.
 * User: lijiaming
 * Date: 2017/12/3
 * Time: 下午8:44
 */

namespace App\Admin\Controllers;


use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
