<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Services\TfidfService;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'admin@gmail.com',
            'role' => 'admin',
            'password' => 'password',
        ]);

        $this->call([
            JenisSuratMasukSeeder::class,
            SuratMasukSeeder::class,
            SuratKeluarSeeder::class,
            ]);
            TfidfService::recalculateGlobalTFIDF();
    }
}
