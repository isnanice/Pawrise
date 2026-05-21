<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('adoption_applications', function (Blueprint $table) {
            // Ganti enum lama dengan yang sudah include 'draft'
            $table->enum('status', ['draft', 'menunggu', 'disetujui', 'ditolak'])
                  ->default('menunggu')
                  ->change();
        });
    }

    public function down(): void
    {
        Schema::table('adoption_applications', function (Blueprint $table) {
            $table->enum('status', ['menunggu', 'disetujui', 'ditolak'])
                  ->default('menunggu')
                  ->change();
        });
    }
};