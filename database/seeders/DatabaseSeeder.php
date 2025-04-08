<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = Role::create(["name" => 'admin']);
        $bank = Role::create(["name" => 'bank']);
        $student = Role::create(["name" => 'student']);

        User::create([
            'name'=>'admin',
            'email'=>'admin@gmail.com',
            'password'=> Hash::make('admin'),
            'role_id' => $admin->id,
        ]);
        User::create([
            'name'=>'bank',
            'email'=>'bank@gmail.com',
            'password'=> Hash::make('bank'),
            'role_id' => $bank->id,
        ]);
        User::create([
            'name'=>'student',
            'email'=>'student@gmail.com',
            'password'=> Hash::make('student'),
            'role_id' => $student->id,
        ]);
    }
}
