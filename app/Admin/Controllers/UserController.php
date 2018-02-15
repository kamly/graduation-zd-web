<?php
/**
 * Created by PhpStorm.
 * User: lijiaming
 * Date: 2017/12/3
 * Time: 下午8:34
 */

namespace App\Admin\Controllers;


use App\Models\AdminRole;
use App\Models\AdminUser;

class UserController extends Controller
{

    // 管理员人员列表页面
    public function index()
    {
        $users = AdminUser::paginate(10);
        return view('admin/user/index', compact('users'));
    }

    // 管理员人员创建页面
    public function create()
    {
        return view('admin/user/add');
    }

    // 管理员人员创建页面动作
    public function store()
    {
        $this->validate(request(), [
            'name' => 'required|min:4|max:10|unique:admin_users,name',
            'password' => 'required|min:4|max:16'
        ], [
            'name.min' => '名字不能少于4个字',
            'name.max' => '名字不能超过10个字',
            'name.unique' => '名字出现重复',
            'name.required' => '必须填写名字',
            'password.required' => '必须填写密码',
            'password.min' => '密码不能少于4个字',
            'password.max' => '密码不能超过16个字'
        ]);

        $name = request('name');
        $password = bcrypt(request('password'));
        AdminUser::create(compact('name', 'password'));

        return redirect('/admin/users')->with('status', '用户创建成功');

    }

    // 管理员人员修改页面
    public function edit(AdminUser $user)
    {
        return view('admin/user/update', compact('user'));
    }

    // 管理员人员修改页面动作
    public function update(AdminUser $user)
    {
        $this->validate(request(), [
            'name' => 'required|min:4|max:10',
            'password' => 'required|min:4|max:16'
        ], [
            'name.min' => '名字不能少于4个字',
            'name.max' => '名字不能超过10个字',
            'name.unique' => '名字出现重复',
            'name.required' => '必须填写名字',
            'password.required' => '必须填写密码',
            'password.min' => '密码不能少于4个字',
            'password.max' => '密码不能超过16个字'
        ]);

        $is_repeat_name = AdminUser::where(['name' => request('name')])->first();
        if ($is_repeat_name && $is_repeat_name->id != $user->id) {
            return back()->withErrors(array('message' => '名字已经被注册'));
        }

        $user->name = request('name');
        $user->password = bcrypt(request('password'));
        $user->save();

        return redirect('/admin/users')->with('status', '用户修改成功');
    }

    public function delete(AdminUser $user)
    {
        $user->delete();
        return redirect('/admin/users')->with('status', '用户删除成功');
    }

    // 管理员角色
    public function role(AdminUser $user)
    {
        $roles = AdminRole::all();
        $myRoles = $user->roles;
        return view('/admin/user/role', compact('roles', 'myRoles', 'user'));
    }

    // 管理员角色保存
    public function storeRole(AdminUser $user)
    {
        $this->validate(request(), [
            'roles' => 'array'
        ], [
            'roles.array' => '提交数据格式错误'
        ]);

        $roles = AdminRole::findMany(request('roles'));
        $myRole = $user->roles;

        // 要增加部分
        $addRoles = $roles->diff($myRole);
        foreach ($addRoles as $role) {
            $user->assignRole($role);
        }

        // 要删除部分
        $delRoles = $myRole->diff($roles);
        foreach ($delRoles as $role) {
            $user->delRole($role);
        }
        return redirect("/admin/users/{$user->id}/role")->with('status', '管理员角色保存成功');
    }
}