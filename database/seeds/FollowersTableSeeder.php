<?php

use Illuminate\Database\Seeder;

class FollowersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = \App\Models\User::first();
        $otherUsers = \App\Models\User::where('id', '<>', $user->id)->get();

        $user->follow($otherUsers->pluck('id')->toArray());
        $otherUsers->each(function ($other) use ($user) {
            $other->follow($user->id);
        });
    }
}
