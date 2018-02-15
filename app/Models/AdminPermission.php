<?php

namespace App\Models;


class AdminPermission extends Base
{
    //
    protected $table = "admin_permissions";

    // 权限属于哪个角色
    public function roles()
    {
        return $this->belongsToMany(\App\Models\AdminRole::class, 'admin_permission_role', 'permission_id', 'role_id')->withPivot(['permission_id','role_id']);
    }

}
