<?php
/**
 * Created by PhpStorm.
 * User: lijiaming
 * Date: 2018/1/28
 * Time: 下午1:36
 */

namespace App\Providers;


use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Str;


class HashEloquentUserProvider extends EloquentUserProvider
{
    /**
     * Validate a user against the given credentials.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable $user
     * @param  array $credentials
     * @return bool
     */
    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        $plain = $credentials['password'];
        $authPassword = $user->getAuthPassword();
        // sha1(salt.sha1(salt.password))
        return sha1(config('myConfig.salt') . sha1(config('myConfig.salt') . $plain)) == $authPassword['password'];
    }
}