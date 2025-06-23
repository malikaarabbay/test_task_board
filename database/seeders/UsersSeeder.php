<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminRole = Role::where('name', 'admin')->firstOrFail();
        $managerRole = Role::where('name', 'manager')->firstOrFail();
        $employeeRole = Role::where('name', 'employee')->firstOrFail();

        // Админ
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'Admin User', 'password' => bcrypt('password')]
        );
        $admin->roles()->syncWithoutDetaching([$adminRole->id]);

        // Менеджеры
        $managers = User::factory(2)->create();
        foreach ($managers as $manager) {
            $manager->roles()->syncWithoutDetaching([$managerRole->id]);
        }

        // Сотрудники
        $employees = User::factory(5)->create();
        foreach ($employees as $employee) {
            $employee->roles()->syncWithoutDetaching([$employeeRole->id]);
        }
    }
}
