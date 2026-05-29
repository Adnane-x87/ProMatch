<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Role::findOrCreate('owner');
        Role::findOrCreate('employee');
        Role::findOrCreate('tenant');

        $this->call(CsvSeeder::class);
    }
}
