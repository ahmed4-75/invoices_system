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
        Schema::create('receipts_lists', function (Blueprint $table) {
            $table->id();
            $table->decimal('value',8,2);
            $table->decimal('due_value',8,2);
            $table->foreignId('invoice_id')->constrained("invoices_lists")->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamp('created_at')->useCurrent();
            $table->softDeletes();
            $table->string('file_name',500)->nullable()->comment('Names of uploaded files, separated by comma if multiple');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receipts_lists');
    }
};
