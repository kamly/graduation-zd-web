<?php
/**
 * Created by PhpStorm.
 * User: lijiaming
 * Date: 2017/12/10
 * Time: 下午7:10
 */

namespace App\Http\Controllers;


class NoticeController extends Controller
{

    public function index()
    {
        $user = \Auth::user();
        $notices = $user->notices;
        return view('notice/index', compact('notices'));
    }

}