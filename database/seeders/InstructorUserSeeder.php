<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class InstructorUserSeeder extends Seeder
{
    public function run(): void
    {
        $instructorEmail = 'instructor@lms.test';

        $instructor = User::firstOrCreate(
            ['email' => $instructorEmail],
            [
                'name' => 'Instructor',
                'password' => 'password',
            ]
        );

        if (!$instructor->hasRole('instructor')) {
            $instructor->assignRole('instructor');
        }
    }
}
