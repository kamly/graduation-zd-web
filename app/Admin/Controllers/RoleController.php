<?php
/**
 * Created by PhpStorm.
 * User: lijiaming
 * Date: 2017/12/8
 * Time: 上午9:47
 */

namespace App\Admin\Controllers;


use App\Models\AdminPermission;
use App\Models\AdminRole;

class RoleController extends Controller
{
    // 角色列表页面
    public function index()
    {
        $roles = AdminRole::paginate(10);
        return view('/admin/role/index', compact('roles'));
    }

    // 角色创建页面
    public function create()
    {
        return view('/admin/role/add');
    }

    // 角色创建动作
    public function store()
    {
        $this->validate(request(), [
            'name' => 'required|min:3|max:30|unique:admin_roles,name',
            'description' => 'required|max:100'
        ], [
            'name.min' => '名字不能少于3个字',
            'name.max' => '名字不能超过30个字',
            'name.unique' => '名字出现重复',
            'name.required' => '必须填写名字',
            'description.required' => '必须填写描述',
            'description.max' => '描述不能超过100个字',
        ]);
        AdminRole::create(request(['name', 'description']));
        return redirect('/admin/roles')->with('status', '角色创建成功');
    }

    // 角色修改页面
    public function edit(AdminRole $role)
    {
        return view('admin/role/update', compact('role'));

    }

    // 角色修改页面动作
    public function update(AdminRole $role)
    {
        $this->validate(request(), [
            'name' => 'required|min:3|max:30',
            'description' => 'required|max:100'
        ], [
            'name.min' => '名字不能少于3个字',
            'name.max' => '名字不能超过30个字',
            'name.unique' => '名字出现重复',
            'name.required' => '必须填写名字',
            'description.required' => '必须填写描述',
            'description.max' => '描述不能超过100个字',
        ]);

        $is_repeat_name = AdminRole::where(['name' => request('name')])->first();
        if ($is_repeat_name && $is_repeat_name->id != $role->id) {
            return back()->withErrors(array('message' => '名字已经被注册'));
        }

        $role->name = request('name');
        $role->description = request('description');
        $role->save();

        return redirect('/admin/roles')->with('status', '角色修改成功');
    }

    // 角色删除
    public function delete(AdminRole $role)
    {
        $role->delete();
        return redirect('/admin/roles')->with('status', '角色删除成功');
    }

    // 角色权限列表
    public function permission(AdminRole $role)
    {
        // 所有权限
        $permissions = AdminPermission::all();
        // 当前角色的权限
        $myPermission = $role->permissions;

        return view('/admin/role/permission', compact('permissions', 'myPermission', 'role'));
    }

    // 角色权限保存
    public function storePermission(AdminRole $role)
    {
        $this->validate(request(), [
            'permissions' => 'required'
        ], [
            'roles.array' => '提交数据格式错误'
        ]);

        $permissions = AdminPermission::findMany(request('permissions'));
        $myPermission = $role->permissions;

        // 要增加部分
        $addPermissions = $permissions->diff($myPermission);
        foreach ($addPermissions as $permission) {
            $role->grantPermission($permission);
        }

        // 要删除部分
        $delPermissions = $myPermission->diff($permissions);
        foreach ($delPermissions as $permission) {
            $role->delPermission($permission);
        }

        return redirect("admin/roles/{$role->id}/permission")->with('status', '角色权限保存成功');
    }
}