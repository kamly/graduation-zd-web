<?php

namespace App\Providers;

use App\Models\AdminPermission;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
//        'App\Model' => 'App\Policies\ModelPolicy',
        'App\Models\Post' => 'App\Policies\PostPolicy'
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // 拿出所有权限
        $permissions = AdminPermission::all();
        // 定义门卫
        foreach ($permissions as $permission) {
            Gate::define($permission->name, function ($user) use ($permission) {
                return $user->hasPermission($permission);
            });
        }

        \Auth::provider('hash-eloquent', function ($app, $config) {
            return new HashEloquentUserProvider($this->app['hash'], $config['model']);
        });
    }
}
