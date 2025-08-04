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
        Schema::create('batches', function (Blueprint $table) {
            $table->id();            
            $table->foreignId('supplier_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('code')->unique();
            $table->unsignedInteger('amount_of_packs');
            $table->unsignedInteger('amount_of_units_per_pack');
            $table->unsignedInteger('total_units');
            $table->unsignedInteger('available_units');
            $table->decimal('price_per_pack', 10, 2);
            $table->decimal('price_per_unit', 10, 2);
            $table->date('expiration_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batches');
    }
};
