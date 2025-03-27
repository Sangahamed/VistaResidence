<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {


        Admin::create([
            'name'=>'ange axel guiria',
            'username'=>'Responable',
            'email'=>'adminaxel@email.com',
            'password'=>Hash::make('12345678@')
        ]);

        Admin::create([
            'name'=>'Marie poho',
            'username'=>'Assistance',
            'email'=>'adminpoho@email.com',
            'password'=>Hash::make('12345678@')
        ]);

        Admin::create([
            'name'=>'Sanga hamed',
            'username'=>'SuperUser',
            'email'=>'bsangahamed@gmail.com',
            'password'=>Hash::make('12345678@')
        ]);
    }
}
