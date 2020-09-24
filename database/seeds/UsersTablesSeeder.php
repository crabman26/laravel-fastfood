<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\User;

class UsersTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        User::create([
            'name'    => 'Alex Raven',
            'email'    => 'alex_raven@gmail.com',
            'password'   =>  Hash::make('password'),
            'remember_token' =>  str_random(10),
        ]);
    }
}
