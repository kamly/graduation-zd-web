<?php
/**
 * Created by PhpStorm.
 * User: lijiaming
 * Date: 2017/12/13
 * Time: 下午9:17
 */

return [

    # 加密
    'token_base' => env('SECRET_TOKEN_BASE', '12345'), // 位数随意
    'des_key' => env('DES_KEY', '12345'),  // 位数随意
    'des_iv' => env('DES_IV', '12341234'),  // 一定要8位

    # salt
    'salt' => env('SALT', '12345'),  // 这个可以每个用户都不一样，并且设置有效时间
];