<?php

namespace App\Console\Commands;

use App\Models\AdminPermission;
use App\Models\AdminRole;
use App\Models\AdminUser;
use Illuminate\Console\Command;

class SupperRootInit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'SupperRoot:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'init SupperRoot';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('============= 开始 root 用户 =============');
        $adminUser = new AdminUser();
        $adminUser->name = 'root';
        $adminUser->password = bcrypt('root');
        $adminUser->save();
        $this->info('============= 结果 root 用户 =============');

        $this->info('============= 开始 root 角色 =============');
        $adminRole = new AdminRole();
        $adminRole->name = '超级管理员';
        $adminRole->description = '超级管理员';
        $adminRole->save();
        $this->info('============= 结果 root 角色 =============');

        $this->info('============= 开始 4种权限 =============');
        $adminPermission_1 = new AdminPermission();
        $adminPermission_1->name = 'system';
        $adminPermission_1->description = '系统管理';
        $adminPermission_1->save();


        $adminPermission_2 = new AdminPermission();
        $adminPermission_2->name = 'post';
        $adminPermission_2->description = '文章管理';
        $adminPermission_2->save();

        $adminPermission_3 = new AdminPermission();
        $adminPermission_3->name = 'topic';
        $adminPermission_3->description = '专题管理';
        $adminPermission_3->save();

        $adminPermission_4 = new AdminPermission();
        $adminPermission_4->name = 'notice';
        $adminPermission_4->description = '通知管理';
        $adminPermission_4->save();
        $this->info('============= 结果 4种权限 =============');

        $this->info('============= 开始 root 用户 赋予 root 角色 =============');
        $adminUser->assignRole($adminRole);
        $this->info('============= 结果 root 用户 赋予 root 角色 =============');

        $this->info('============= 开始 4种权限 赋予 root 角色 =============');
        $adminRole->grantPermission($adminPermission_1);
        $adminRole->grantPermission($adminPermission_2);
        $adminRole->grantPermission($adminPermission_3);
        $adminRole->grantPermission($adminPermission_4);
        $this->info('============= 结果 4种权限 赋予 root 角色 =============');
    }
}
