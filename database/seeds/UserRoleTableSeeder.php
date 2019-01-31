<?php

use Illuminate\Database\Seeder;
use App\Permission;
use App\User;
use App\Role;

class UserRoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::where('name', '=', 'admin')->first();
        $user->roles()->attach(1);
    }
}
