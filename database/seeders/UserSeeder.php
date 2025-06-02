<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil role
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $userRole = Role::firstOrCreate(['name' => 'user']);

        foreach (range(1, 10) as $i) {
            $user = User::create([
                'name' => "User $i",
                'email' => "user$i@example.com",
                'password' => Hash::make('password'),
                'avatar' => "avatars/$i.jpg",
            ]);

            // Assign role: 1-2 admin, sisanya user
            if ($i <= 2) {
                $user->assignRole($adminRole);
            } else {
                $user->assignRole($userRole);
            }
        }
    }
}
