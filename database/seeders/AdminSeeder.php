<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@m3allemclick.ma'],
            [
                'name'     => 'Admin M3allemClick',
                'phone'    => '0600000000',
                'password' => Hash::make('Admin@12345'),
                'role'     => 'admin',
                'status'   => 'actif',
            ]
        );
    }
}
