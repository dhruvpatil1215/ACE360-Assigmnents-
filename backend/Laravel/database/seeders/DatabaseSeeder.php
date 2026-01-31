<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Employee;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create 5 companies
        $companies = Company::factory(5)->create();

        // Create employees for each company
        foreach ($companies as $company) {
            // Create 2-3 managers (employees without managers) per company
            $managers = Employee::factory(fake()->numberBetween(2, 3))
                ->create([
                    'company_id' => $company->id,
                    'manager_id' => null,
                ]);

            // Create 5-8 regular employees per company, assigning them to managers
            foreach ($managers as $manager) {
                Employee::factory(fake()->numberBetween(2, 4))
                    ->create([
                        'company_id' => $company->id,
                        'manager_id' => $manager->id,
                    ]);
            }
        }

        $this->command->info('Seeded ' . Company::count() . ' companies and ' . Employee::count() . ' employees.');
    }
}
