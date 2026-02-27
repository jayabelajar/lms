<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->string('nim', 50)->nullable()->after('email');
            $table->string('nip', 50)->nullable()->after('nim');
            $table->string('semester', 50)->nullable()->after('nip');
            $table->string('angkatan', 20)->nullable()->after('semester');
            $table->string('kelas', 50)->nullable()->after('angkatan');
            $table->string('jurusan', 100)->nullable()->after('kelas');
            $table->string('prodi', 100)->nullable()->after('jurusan');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropColumn(['nim', 'nip', 'semester', 'angkatan', 'kelas', 'jurusan', 'prodi']);
        });
    }
};
