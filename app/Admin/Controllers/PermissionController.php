<?php
/**
 * Created by PhpStorm.
 * User: lijiaming
 * Date: 2017/12/8
 * Time: 上午9:48
 */

namespace App\Admin\Controllers;


use App\Models\AdminPermission;

class PermissionController extends Controller
{
    // 权限列表页面
    public function index()
    {
        $permissions = AdminPermission::paginate(10);
        return view('/admin/permission/index', compact('permissions'));
    }

    // 权限创建页面
    public function create()
    {
        return view('/admin/permission/add');
    }

    // 权限创建动作
    public function store()
    {
        $this->validate(request(), [
            'name' => 'required|min:3|max:30|unique:admin_permissions,name',
            'description' => 'required|max:100'
        ], [
            'name.min' => '名字不能少于3个字',
            'name.max' => '名字不能超过30个字',
            'name.unique' => '名字出现重复',
            'name.required' => '必须填写名字',
            'description.required' => '必须填写描述',
            'description.max' => '描述不能超过100个字',
        ]);

        AdminPermission::create(request(['name', 'description']));

        return redirect('/admin/permissions');
    }

    // 角色修改页面
    public function edit(AdminPermission $permission)
    {
        return view('/admin/permission/update', compact('permission'));
    }

    // 角色修改页面
    public function update(AdminPermission $permission)
    {
        $this->validate(request(), [
            'description' => 'required|max:100'
        ], [
            'description.required' => '必须填写描述',
            'description.max' => '描述不能超过100个字',
        ]);

        $permission->description = request('description');
        $permission->save();

        return redirect('/admin/permissions')->with('status', '权限修改成功');
    }
}