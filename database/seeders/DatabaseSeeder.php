<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'nom' => 'Test User',
            'prenom' => 'Test',
            'password' => bcrypt('password'),
            'role_id' => 1,
            'telephone' => '1234567890',
           
            'email' => 'test@example.com',
        ]);
        $this->call([
        PermissionSeeder::class,
    ]);
    }
}
