<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@unguspa.com')],
            ['name' => env('ADMIN_NAME', 'Admin Ungu Spa'), 'password' => env('ADMIN_PASSWORD', 'UnguSpa@2026'), 'email_verified_at' => now()],
        );

        $this->call(ArticleSeeder::class);
    }
}
