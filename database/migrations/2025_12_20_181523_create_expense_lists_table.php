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
        Schema::create('expense_lists', function (Blueprint $table) {
            $table->id();
            $table->string('user');
            $table->string('expense_number');
            $table->string('creditor_name');
            $table->decimal('expense_value',8,2);
            $table->string('status',15); 
            $table->tinyInteger('value_status')->default(0); 
            $table->text('notes')->nullable();
            $table->date('pay_date');
            $table->timestamp('expense_date')->useCurrent(); 
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->softDeletes();
            $table->string('file_name',500)->nullable()->comment('Names of uploaded files, separated by comma if multiple');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expense_lists');
    }
};
