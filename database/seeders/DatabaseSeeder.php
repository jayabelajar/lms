<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            AdminUserSeeder::class,
            InstructorUserSeeder::class,
            StudentUserSeeder::class,
            CourseEnrollmentSeeder::class,
            LmsContentSeeder::class,
        ]);
    }
}
