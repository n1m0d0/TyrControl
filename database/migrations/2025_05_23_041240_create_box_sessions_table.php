<?php

use App\Enums\BoxSessionStatusEnum;
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
        Schema::create('box_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('box_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->decimal('opening_amount', 10, 2)->default(0);
            $table->timestamp('opened_at');
            $table->text('opening_notes')->nullable();
            $table->decimal('closing_amount', 10, 2)->nullable();
            $table->decimal('expected_amount', 10, 2)->nullable();
            $table->decimal('difference', 10, 2)->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->text('closing_notes')->nullable();
            $table->string('status')->default(BoxSessionStatusEnum::OPEN->value);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('box_sessions');
    }
};
