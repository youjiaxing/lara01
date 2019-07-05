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
        factory(\App\Models\User::class)->times(110)->create()->each(function (\App\Models\User $user) {
            $user->statuses()->saveMany(factory(\App\Models\Status::class)->times(rand(15, 35))->make());
        });

        $user = \App\Models\User::find(1);
        $user->name = 'yjx';
        $user->email = '287009007@qq.com';
        $user->is_admin = true;
        $user->save();
    }
}
