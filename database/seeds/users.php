<?php

use Illuminate\Database\Seeder;

class users extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('users')->insert([
          'name' => 'flatko',
          'email' => 'flatko@gmail.com',
          'password' => '$2y$10$nYFdQJ442eOfRc0ob6fT2.Qs9XOMT6vWSJp2rPEuRg3m5BP8lW8Py',
          'games' => '0',
          'won' => '0'
      ]);
    }
}
