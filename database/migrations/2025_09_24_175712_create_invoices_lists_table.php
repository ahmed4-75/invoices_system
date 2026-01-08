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
        Schema::create('invoices_lists', function (Blueprint $table) {
            $table->id(); 
            $table->string('user'); 
            $table->string('invoice_number'); 
            $table->string('customer_name');
            $table->decimal('invoice_value',8,2);
            $table->decimal('discount_value',8,2);
            $table->decimal('vat_value',8,2);
            $table->decimal('total_value',8,2);
            $table->date('due_date');
            $table->string('status',15); 
            $table->tinyInteger('value_status')->default(0); 
            $table->text('notes')->nullable();
            $table->timestamp('invoice_date')->useCurrent(); 
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices_lists');
    }
};
