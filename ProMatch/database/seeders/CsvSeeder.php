<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Owner;
use App\Models\Tenant;
use App\Models\Employee;
use App\Models\Field;
use App\Models\TimeSlot;
use App\Models\Reservation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;

class CsvSeeder extends Seeder
{
    public function run(): void
    {
        // Disable FK checks so we can truncate freely
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Truncate in reverse dependency order
        Reservation::truncate();
        TimeSlot::truncate();
        Field::truncate();
        Employee::truncate();
        Tenant::truncate();
        Owner::truncate();
        User::truncate();
        $this->truncatePermissionTables();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        Role::findOrCreate('owner');
        Role::findOrCreate('employee');
        Role::findOrCreate('tenant');

        // Seed in dependency order
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

            if ($modelClass === \App\Models\TimeSlot::class) {
                $data['date'] = now()->toDateString();
            }

            if ($modelClass === \App\Models\Reservation::class && isset($data['request_date'])) {
                $timePart = '12:00:00';
                $parts = explode(' ', $data['request_date']);

                if (count($parts) > 1) {
                    $timePart = $parts[1];
                }

                $data['request_date'] = now()->toDateString() . ' ' . $timePart;
            }

            if ($modelClass === User::class) {
                $role = $data['role'] ?? null;
                unset($data['role']);

                $user = User::create($data);

                if ($role) {
                    Role::findOrCreate($role);
                    $user->assignRole($role);
                }

                continue;
            }

            $modelClass::create($data);
        }

        fclose($file);
        $this->command->info("Seeded {$filename}");
    }

    private function truncatePermissionTables(): void
    {
        foreach (['model_has_permissions', 'model_has_roles', 'role_has_permissions', 'permissions', 'roles'] as $table) {
            if (Schema::hasTable($table)) {
                DB::table($table)->truncate();
            }
        }
    }
}
