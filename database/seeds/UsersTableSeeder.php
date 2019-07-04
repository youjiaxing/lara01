<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\User::class)->times(110)->create();

        $user = \App\Models\User::find(1);
        $user->name = 'yjx';
        $user->email = '287009007@qq.com';
        $user->is_admin = true;
        $user->save();
    }
}
