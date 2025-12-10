<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('school_majors', function (Blueprint $table) {
        // Menambah kolom 'tuition_fee' (biaya SPP) dengan default 0
        $table->decimal('tuition_fee', 10, 2)->default(0)->after('name');
    });
}

public function down()
{
    Schema::table('school_majors', function (Blueprint $table) {
        $table->dropColumn('tuition_fee');
    });
}
};
