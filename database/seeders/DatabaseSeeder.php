<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory()->count(3)->create()->each(function($u) {
            $u->questions()
              ->saveMany(
                  \App\Models\Question::factory()->count(5)->make()
              )
            ->each(function ($q){
                $q->answers()->saveMany(\App\Models\Answer::factory()->count(5)->make());
            });
        });

    }
}
