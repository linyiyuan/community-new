<?php

use Illuminate\Database\Seeder;
use App\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = new Role();

        $role->name = 'super_admin';
        $role->display_name = '超级管理员';
        $role->description = '拥有最大的权限，可以对后台所有功能进行管理 能对所有管理员进行管理';
        $role->save();
    }
}
