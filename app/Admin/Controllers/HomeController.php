<?php
/**
 * Created by PhpStorm.
 * User: lijiaming
 * Date: 2017/12/3
 * Time: 下午9:27
 */

namespace App\Admin\Controllers;


class HomeController extends Controller
{
    public function index()
    {
        return view('admin/home/index');
    }
}