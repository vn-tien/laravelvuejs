<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;

class UsersQuestionsAnswersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('answers')->delete();
        \DB::table('questions')->delete();
        \DB::table('users')->delete();

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
