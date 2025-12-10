<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
{
    Schema::table('school_majors', function (Blueprint $table) {
        // Kita tambah kolom 'monthly_fee' (biaya bulanan)
        $table->integer('monthly_fee')->default(0)->after('name'); 
    });
}

public function down(): void
{
    Schema::table('school_majors', function (Blueprint $table) {
        $table->dropColumn('monthly_fee');
    });
}
};
