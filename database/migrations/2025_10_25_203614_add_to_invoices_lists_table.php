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
        Schema::table('invoices_lists', function (Blueprint $table) {
            $table->string('file_name',500)->nullable()->comment('Names of uploaded files, separated by comma if multiple');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices_lists', function (Blueprint $table) {
            $table->dropColumn('file_name');
        });
    }
};
