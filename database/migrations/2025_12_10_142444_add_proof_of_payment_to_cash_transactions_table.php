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
    Schema::table('cash_transactions', function (Blueprint $table) {
        // HAPUS bagian ->after('note')
        // Biarkan default (akan masuk ke urutan terakhir)
        $table->string('proof_of_payment')->nullable(); 
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    Schema::table('cash_transactions', function (Blueprint $table) {
        $table->dropColumn('proof_of_payment');
    });
}
};
