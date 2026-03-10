<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Owner;
use App\Models\Tenant;
use App\Models\Employee;
use App\Models\Field;
use App\Models\TimeSlot;
use App\Models\Reservation;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->seedCsv(User::class, 'users.csv');
        $this->seedCsv(Owner::class, 'owners.csv');
        $this->seedCsv(Tenant::class, 'tenants.csv');
        $this->seedCsv(Employee::class, 'employees.csv');
        $this->seedCsv(Field::class, 'fields.csv');
        $this->seedCsv(TimeSlot::class, 'time_slots.csv');
        $this->seedCsv(Reservation::class, 'reservations.csv');
    }

    private function seedCsv($modelClass, $filename)
    {
        $path = database_path("dataCSV/{$filename}");
        if (!file_exists($path)) {
            $this->command->error("File not found: {$path}");
            return;
        }

        $file = fopen($path, 'r');
        $headers = fgetcsv($file);

        while (($row = fgetcsv($file)) !== false) {
            if (empty(array_filter($row))) {
                continue; // Skip empty rows
            }
            
            $data = array_combine($headers, $row);
            
            if (isset($data['password'])) {
                $data['password'] = bcrypt($data['password']);
            }

            $modelClass::create($data);
        }

        fclose($file);
        $this->command->info("Seeded {$filename}");
    }
}
