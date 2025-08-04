<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@ajatic.com',
        ])->assignRole(RoleEnum::ADMINISTRATOR->value);

        $this->createUsersWithRole(RoleEnum::SELLER, 50);
        $this->createUsersWithRole(RoleEnum::SUPERVISOR, 20);
        $this->createUsersWithRole(RoleEnum::CHECKER, 10);
    }

    private function createUsersWithRole(RoleEnum $role, int $count): void
    {
        User::factory($count)
            ->create()
            ->each(fn($user) => $user->assignRole($role->value));
    }
}
