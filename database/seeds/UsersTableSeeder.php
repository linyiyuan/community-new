<?php

use Illuminate\Database\Seeder;
use App\User;


class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User();

        $user->name = 'admin';
        $user->email = 'admin@appgame.com';
        $user->password = bcrypt('admin@appgame.com');
        $user->img = '';
        $user->save();
    }
}
