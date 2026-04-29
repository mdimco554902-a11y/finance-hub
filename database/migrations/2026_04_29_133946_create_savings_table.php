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
    Schema::create('savings', function (Blueprint $table) {
        $table->id();
        // This line is the most important one!
        $table->foreignId('user_id')->constrained()->onDelete('cascade'); 
        $table->string('title');
        $table->decimal('target_amount', 15, 2);
        $table->decimal('current_amount', 15, 2)->default(0);
        $table->string('color')->default('#10b981');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('savings');
    }
};
