<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class StudentUserSeeder extends Seeder
{
    public function run(): void
    {
        $studentEmail = 'student@lms.test';

        $student = User::firstOrCreate(
            ['email' => $studentEmail],
            [
                'name' => 'Student',
                'password' => 'password',
            ]
        );

        if (!$student->hasRole('student')) {
            $student->assignRole('student');
        }
    }
}
