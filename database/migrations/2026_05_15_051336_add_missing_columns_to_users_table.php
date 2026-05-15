<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Cek apakah kolom role sudah ada
            if (!Schema::hasColumn('users', 'role')) {
                $table->enum('role', ['super_admin', 'admin'])->default('admin')->after('email');
            }
            
            // Cek apakah kolom is_active sudah ada
            if (!Schema::hasColumn('users', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('role');
            }
            
            // Cek apakah kolom assigned_books sudah ada
            if (!Schema::hasColumn('users', 'assigned_books')) {
                $table->json('assigned_books')->nullable()->after('is_active');
            }
            
            // Cek apakah kolom photo sudah ada
            if (!Schema::hasColumn('users', 'photo')) {
                $table->string('photo')->nullable()->after('assigned_books');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'is_active', 'assigned_books', 'photo']);
        });
    }
};