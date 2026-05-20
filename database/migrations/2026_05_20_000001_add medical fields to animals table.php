<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('animals', function (Blueprint $table) {
            $table->boolean('vaccinated_distemper')->default(false)->after('vaccinated');
            $table->boolean('dewormed')->default(false)->after('sterilized');
            $table->boolean('flea_free')->default(false)->after('dewormed');
            $table->boolean('special_needs')->default(false)->after('flea_free');
            $table->date('vaccinated_rabies_date')->nullable()->after('special_needs');
            $table->date('vaccinated_distemper_date')->nullable()->after('vaccinated_rabies_date');
        });
    }

    public function down(): void
    {
        Schema::table('animals', function (Blueprint $table) {
            $table->dropColumn([
                'vaccinated_distemper', 'dewormed', 'flea_free', 'special_needs',
                'vaccinated_rabies_date', 'vaccinated_distemper_date',
            ]);
        });
    }
};